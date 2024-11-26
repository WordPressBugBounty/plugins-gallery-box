<?php

/**
 * weDevs Settings API wrapper class
 *
 * @version 1.2 (18-Oct-2015)
 *
 * @author Tareq Hasan
 * @link http://tareq.weDevs.com Tareq's Planet
 * @example src/settings-api.php How to use the class
 */

if (! class_exists('ngallery_box_settings')):
    class ngallery_box_settings
    {

        protected $settings_sections = array();
        protected $settings_fields = array();

        public function __construct()
        {
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        }

        function admin_enqueue_scripts()
        {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery');
        }

        function set_sections($sections)
        {
            $this->settings_sections = $sections;
            return $this;
        }

        function add_section($section)
        {
            $this->settings_sections[] = $section;
            return $this;
        }

        function set_fields($fields)
        {
            $this->settings_fields = $fields;
            return $this;
        }

        function add_field($section, $field)
        {
            $defaults = array(
                'name'  => '',
                'label' => '',
                'desc'  => '',
                'type'  => 'text'
            );

            $arg = wp_parse_args($field, $defaults);
            $this->settings_fields[$section][] = $arg;

            return $this;
        }

        function admin_init()
        {
            foreach ($this->settings_sections as $section) {
                if (false === get_option($section['id'])) {
                    add_option($section['id']);
                }

                $callback = isset($section['callback']) ? $section['callback'] : null;

                if (isset($section['desc']) && !empty($section['desc'])) {
                    $callback = function () use ($section) {
                        echo '<div class="inside">' . esc_html($section['desc']) . '</div>';
                    };
                }

                add_settings_section($section['id'], esc_html($section['title']), $callback, $section['id']);
            }

            foreach ($this->settings_fields as $section => $field) {
                foreach ($field as $option) {
                    $type = isset($option['type']) ? $option['type'] : 'text';

                    $args = array(
                        'id'                => $option['name'],
                        'label_for'         => "{$section}[{$option['name']}]",
                        'desc'              => isset($option['desc']) ? $option['desc'] : '',
                        'name'              => $option['label'],
                        'section'           => $section,
                        'size'              => isset($option['size']) ? $option['size'] : null,
                        'options'           => isset($option['options']) ? $option['options'] : '',
                        'std'               => isset($option['default']) ? $option['default'] : '',
                        'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
                        'type'              => $type,
                    );

                    add_settings_field($section . '[' . $option['name'] . ']', esc_html($option['label']), array($this, 'callback_' . $type), $section, $section, $args);
                }
            }

            foreach ($this->settings_sections as $section) {
                register_setting($section['id'], $section['id'], array($this, 'sanitize_options'));
            }
        }

        public function get_field_description($args)
        {
            if (! empty($args['desc'])) {
                $desc = sprintf('<p class="description">%s</p>', esc_html($args['desc']));
            } else {
                $desc = '';
            }

            return $desc;
        }

        function callback_text($args)
        {
            $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
            $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $type  = isset($args['type']) ? $args['type'] : 'text';

            $html  = sprintf(
                '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"/>',
                $type,
                $size,
                esc_attr($args['section']),
                esc_attr($args['id']),
                $value
            );
            $html  .= $this->get_field_description($args);

            echo wp_kses_post($html);
        }

        function callback_url($args)
        {
            $this->callback_text($args);
        }

        function callback_number($args)
        {
            $this->callback_text($args);
        }

        function callback_checkbox($args)
        {
            $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));

            $html  = '<fieldset>';
            $html  .= sprintf('<label for="wpuf-%1$s[%2$s]">', esc_attr($args['section']), esc_attr($args['id']));
            $html  .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="off" />', esc_attr($args['section']), esc_attr($args['id']));
            $html  .= sprintf('<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', esc_attr($args['section']), esc_attr($args['id']), checked($value, 'on', false));
            $html  .= sprintf('%1$s</label>', esc_html($args['desc']));
            $html  .= '</fieldset>';

            echo wp_kses_post($html);
        }

        function callback_multicheck($args)
        {
            $value = $this->get_option($args['id'], $args['section'], $args['std']);
            $html  = '<fieldset>';

            foreach ($args['options'] as $key => $label) {
                $checked = isset($value[$key]) ? $value[$key] : '0';
                $html    .= sprintf('<label for="wpuf-%1$s[%2$s][%3$s]">', esc_attr($args['section']), esc_attr($args['id']), esc_attr($key));
                $html    .= sprintf('<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', esc_attr($args['section']), esc_attr($args['id']), esc_attr($key), checked($checked, $key, false));
                $html    .= sprintf('%1$s</label><br>', esc_html($label));
            }

            $html .= $this->get_field_description($args);
            $html .= '</fieldset>';

            echo wp_kses_post($html);
        }

        function callback_select($args)
        {
            $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
            $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html  = sprintf('<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, esc_attr($args['section']), esc_attr($args['id']));

            foreach ($args['options'] as $key => $label) {
                $html .= sprintf('<option value="%s"%s>%s</option>', esc_attr($key), selected($value, $key, false), esc_html($label));
            }

            $html .= sprintf('</select>');
            $html .= $this->get_field_description($args);

            echo wp_kses_post($html);
        }

        function callback_textarea($args)
        {
            $value = esc_textarea($this->get_option($args['id'], $args['section'], $args['std']));
            $size  = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';

            $html  = sprintf('<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, esc_attr($args['section']), esc_attr($args['id']), $value);
            $html  .= $this->get_field_description($args);

            echo wp_kses_post($html);
        }

        function sanitize_options($options)
        {
            foreach ($options as $option_slug => $option_value) {
                $sanitize_callback = $this->get_sanitize_callback($option_slug);

                if ($sanitize_callback) {
                    $options[$option_slug] = call_user_func($sanitize_callback, $option_value);
                    continue;
                }
            }

            return $options;
        }

        function get_sanitize_callback($slug = '')
        {
            if (empty($slug)) {
                return false;
            }

            foreach ($this->settings_fields as $section => $options) {
                foreach ($options as $option) {
                    if ($option['name'] !== $slug) {
                        continue;
                    }

                    return isset($option['sanitize_callback']) && is_callable($option['sanitize_callback']) ? $option['sanitize_callback'] : false;
                }
            }

            return false;
        }

        function get_option($option, $section, $default = '')
        {
            $options = get_option($section);
            return isset($options[$option]) ? $options[$option] : $default;
        }

        function show_navigation()
        {
            $html = '<h2 class="nav-tab-wrapper">';
            foreach ($this->settings_sections as $tab) {
                $html .= sprintf('<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', esc_attr($tab['id']), esc_html($tab['title']));
            }
            $html .= '</h2>';
            echo wp_kses_post($html);
        }

        function show_forms()
        {
?>
            <div class="metabox-holder">
                <?php foreach ($this->settings_sections as $form) { ?>
                    <div id="<?php echo esc_attr($form['id']); ?>" class="group" style="display: none;">
                        <form method="post" action="options.php">
                            <?php
                            do_action('wsa_form_top_' . $form['id'], $form);
                            settings_fields($form['id']);
                            do_settings_sections($form['id']);
                            do_action('wsa_form_bottom_' . $form['id'], $form);
                            ?>
                            <div style="padding-left: 10px">
                                <?php submit_button(); ?>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>
        <?php
            $this->script();
        }

        function script()
        {
        ?>
            <script>
                jQuery(document).ready(function($) {
                    $('.wp-color-picker-field').wpColorPicker();
                    $('.group').hide();
                    var activetab = '';
                    if (typeof(localStorage) != 'undefined') {
                        activetab = localStorage.getItem("activetab");
                    }
                    if (activetab != '' && $(activetab).length) {
                        $(activetab).fadeIn();
                    } else {
                        $('.group:first').fadeIn();
                    }
                    if (activetab != '' && $(activetab + '-tab').length) {
                        $(activetab + '-tab').addClass('nav-tab-active');
                    } else {
                        $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                    }
                    $('.nav-tab-wrapper a').click(function(evt) {
                        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                        $(this).addClass('nav-tab-active').blur();
                        var clicked_group = $(this).attr('href');
                        if (typeof(localStorage) != 'undefined') {
                            localStorage.setItem("activetab", $(this).attr('href'));
                        }
                        $('.group').hide();
                        $(clicked_group).fadeIn();
                        evt.preventDefault();
                    });

                    $('.wpsa-browse').on('click', function(event) {
                        event.preventDefault();
                        var self = $(this);
                        var file_frame = wp.media.frames.file_frame = wp.media({
                            title: self.data('uploader_title'),
                            button: {
                                text: self.data('uploader_button_text'),
                            },
                            multiple: false
                        });
                        file_frame.on('select', function() {
                            attachment = file_frame.state().get('selection').first().toJSON();
                            self.prev('.wpsa-url').val(attachment.url);
                        });
                        file_frame.open();
                    });
                });
            </script>

            <style type="text/css">
                .form-table th {
                    padding: 20px 10px;
                }

                #wpbody-content .metabox-holder {
                    padding-top: 5px;
                }
            </style>
<?php
        }
    }
endif;
new ngallery_box_settings();

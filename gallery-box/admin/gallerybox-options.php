<?php
/*
 * Gallery Box options
 * @link              http://gbox.awesomebootstrap.net
 * @since             1.0.0
 * @package           Gallery box wordpress plugin
 * @author Noor alam
 */
if (!class_exists('nGalleryBox_main_options')) :
    class nGalleryBox_main_options
    {

        private $settings_api;

        function __construct()
        {
            $this->settings_api = new ngallery_box_settings;

            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_menu', array($this, 'admin_menu'));
        }

        function admin_init()
        {

            //set the settings
            $this->settings_api->set_sections($this->get_settings_sections());
            $this->settings_api->set_fields($this->get_settings_fields());

            //initialize settings
            $this->settings_api->admin_init();
        }

        function admin_menu()
        {
            add_submenu_page(
                'edit.php?post_type=gallery_box',
                __('Gallery Box settings', 'gbox'),
                __('Gallery Box settings', 'gbox'),
                'manage_options',
                'gallery-box-options.php',
                array($this, 'plugin_page')
            );
        }

        function get_settings_sections()
        {
            $sections = array(
                array(
                    'id' => 'Lightbox_settings',
                    'title' => __('Lightbox settings', 'gbox')
                ),
                array(
                    'id' => 'img_style',
                    'title' => __('All image gallery style', 'gbox')
                ),
                array(
                    'id' => 'youtube_style',
                    'title' => __('Youtube gallery style', 'gbox')
                ),
                array(
                    'id' => 'vimeo_style',
                    'title' => __('Vimeo gallery style', 'gbox')
                ),
                array(
                    'id' => 'iframe_style',
                    'title' => __('Iframe gallery style', 'gbox')
                ),

            );
            return $sections;
        }

        /**
         * Returns all the settings fields
         *
         * @return array settings fields
         */
        function get_settings_fields()
        {
            $settings_fields = array(
                'Lightbox_settings' => array(
                    array(
                        'name'    => 'use_typography',
                        'label'   => __('Gallery Box font', 'gbox'),
                        'desc'    => __('You can use gallery box default font or use your theme font and typography.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'no',
                        'options' => array(
                            'yes' => __('Active', 'gbox'),
                            'no'  => __('Deactive', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'loader_style',
                        'label'   => __('Image preloader style', 'gbox'),
                        'desc'    => __('Select lightbox image preloader style.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'double-bounce',
                        'options' => array(
                            'rotating-plane'   => 'Rotating plane',
                            'double-bounce'   => 'Double bounce',
                            'wave'   => 'wave',
                            'wandering-cubes'   => 'Wandering cubes',
                            'spinner-pulse'   => 'Spinner pulse',
                            'three-bounce'   => 'Three bounce',
                            'cube-grid'   => 'Cube grid',

                        )
                    ),
                    array(
                        'name'    => 'loader_color',
                        'label'   => __('Set lightbox icon color.', 'gbox'),
                        'desc'    => __('The color show in arrow icon and close icon.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#b6b6b6',

                    ),
                    array(
                        'name'    => 'light_border',
                        'label'   => __('Lightbox Image border', 'gbox'),
                        'desc'    => __('Set your image border by px. default value 0', 'gbox'),
                        'type'              => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'light_bcolor',
                        'label'   => __('Set lightbox background color.', 'gbox'),
                        'desc'    => __('The color show in preloader, border and text background.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#d2d2d2',

                    ),

                    array(
                        'name'    => 'use_caption',
                        'label'   => __('lightbox caption', 'gbox'),
                        'desc'    => __('You can show hide lightbox caption.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Active', 'gbox'),
                            'No'  => __('Hide', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'cap_position',
                        'label'   => __('Lightbox caption position', 'gbox'),
                        'desc'    => __('Set gallery lightbox caption position.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'top' => __('top', 'gbox'),
                            'bottom'  => __('bottom', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'show_arrow',
                        'label'   => __('Image Gallery navigation', 'gbox'),
                        'desc'    => __('Gallery navigation only work in image gallery.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Show', 'gbox'),
                            'no'  => __('Hide', 'gbox'),
                        )
                    ),

                ),
                //Image style
                'img_style' => array(
                    array(
                        'name'    => 'img_column',
                        'label'   => __('Image gallery column ', 'gbox'),
                        'desc'    => __('Set your image gallery Column. Some of the animation may not work properly in 4 column.', 'gbox'),
                        'type'              => 'select',
                        'default' => 3,
                        'options' => array(
                            1  => __('one column', 'gbox'),
                            2  => __('Two column', 'gbox'),
                            3  => __('Three column', 'gbox'),
                            4  => __('Four column', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'img_border',
                        'label'   => __('Image border', 'gbox'),
                        'desc'    => __('Set your image border by px. default value 0', 'gbox'),
                        'type'              => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'img_border_color',
                        'label'   => __('Image border color', 'gbox'),
                        'desc'    => __('Set your image border color.', 'gbox'),
                        'type'              => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'img_border_type',
                        'label'   => __('Image border type', 'gbox'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gbox'),
                            'dotted'  => __('Dotted', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'img_animation',
                        'label'   => __('Select hover animation', 'gbox'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for image gallery.', 'gbox'),
                        'type'     => 'select',
                        'default' => 'ehover12',
                        'options'          => array(
                            'ehover1' => __('Animation One', 'cmb2'),
                            'ehover2'   => __('Animation Two', 'cmb2'),
                            'ehover3'     => __('Animation Three', 'cmb2'),
                            'ehover4'     => __('Animation Four', 'cmb2'),
                            'ehover5'     => __('Animation Five', 'cmb2'),

                        )
                    ),
                    array(
                        'name'    => 'img_title_back',
                        'label'   => __('Title background color', 'gbox'),
                        'desc'    => __('Set your image gallery item title background color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'img_title_opacity',
                        'label'   => __('Title background opacity', 'gbox'),
                        'desc'    => __('Set your image gallery item title background opacity.Opacity value 1 to 99', 'gbox'),
                        'type'              => 'number',
                        'default' => 50,

                    ),
                    array(
                        'name'    => 'img_title_color',
                        'label'   => __('Set title color', 'gbox'),
                        'desc'    => __('Set your image gallery item text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'img_title_font',
                        'label'   => __('Set title font size', 'gbox'),
                        'desc'    => __('Default font size is 17px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'img_title_transform',
                        'label'   => __('Select title text transform', 'gbox'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('Uppercase', 'gbox'),
                            'lowercase'  => __('Lowercase', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'img_title_padding',
                        'label'   => __('Set title padding', 'gbox'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'img_btn_font',
                        'label'   => __('Set Button font size', 'gbox'),
                        'desc'    => __('Default font size 14px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'img_btn_color',
                        'label'   => __('Button text color', 'gbox'),
                        'desc'    => __('Set Image gallery item button text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'img_btn_border',
                        'label'   => __('Button border color', 'gbox'),
                        'desc'    => __('Set Image gallery item button border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'img_load_button',
                        'label'   => __('Load more button', 'gbox'),
                        'desc'    => __('Load more button is pro feature.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'pro'  => __('Only available in pro', 'gbox'),
                            'disable'  => __('Disable', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'img_item_number',
                        'label'   => __('Images item number', 'gbox'),
                        'desc'    => __('Select how many item show in first page. pro feature', 'gbox'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'img_load_position',
                        'label'   => __('Load more button position', 'gbox'),
                        'desc'    => __('Select load more button position left, right, center, full width. pro feature', 'gbox'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gbox'),
                            'right'  => __('Right', 'gbox'),
                            'center'  => __('Center', 'gbox'),
                            'full'  => __('Full width', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'img_load_color',
                        'label'   => __('Load more button color', 'gbox'),
                        'desc'    => __('select more button color by this color option. pro feature', 'gbox'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'img_load_bgcolor',
                        'label'   => __('Load more button background color.', 'gbox'),
                        'desc'    => __('select more button background color by this color option. pro feature', 'gbox'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'img_load_color_hover',
                        'label'   => __('Load more button hover color', 'gbox'),
                        'desc'    => __('select more button color by this color option.pro feature', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'img_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color. ', 'gbox'),
                        'desc'    => __('select more button background color by this color option. pro feature', 'gbox'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),

                ),
                //Youtube style settings
                'youtube_style' => array(
                    array(
                        'name'    => 'youtube_column',
                        'label'   => __('Youtube gallery column.', 'gbox'),
                        'desc'    => __('Set your Youtube gallery Column. Some of the animation may not work properly in 4 column.', 'gbox'),
                        'type'    => 'select',
                        'default' => 3,
                        'options' => array(
                            2  => __('Two column', 'gbox'),
                            3  => __('Three column', 'gbox'),
                            4  => __('Four column', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'youtube_auto',
                        'label'   => __('Youtube video auto play.', 'gbox'),
                        'desc'    => __('You can set Youtube video auto paly when open in lightbox.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'yes',
                        'options' => array(
                            'yes'  => __('Active', 'gbox'),
                            'no' => __('Hide', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'youtube_border',
                        'label'   => __('youtube column border', 'gbox'),
                        'desc'    => __('Set your youtube border by px. default value 0', 'gbox'),
                        'type'   => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'youtube_border_color',
                        'label'   => __('youtube column border color', 'gbox'),
                        'desc'    => __('Set your youtube border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'youtube_border_type',
                        'label'   => __('youtube column border type', 'gbox'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gbox'),
                            'dotted'  => __('Dotted', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'you_animation',
                        'label'   => __('Select hover animation', 'gbox'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for Youtube video gallery.', 'gbox'),
                        'type'     => 'select',
                        'default' => 'ehover5',
                        'options'          => array(
                            'ehover1' => __('Animation One', 'cmb2'),
                            'ehover2'   => __('Animation Two', 'cmb2'),
                            'ehover3'     => __('Animation Three', 'cmb2'),
                            'ehover4'     => __('Animation Four', 'cmb2'),
                            'ehover5'     => __('Animation Five', 'cmb2'),

                        )
                    ),
                    array(
                        'name'    => 'you_title_back',
                        'label'   => __('Title background color', 'gbox'),
                        'desc'    => __('Set your Youtube gallery item title background color.', 'gbox'),
                        'type'     => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'you_title_opacity',
                        'label'   => __('Title background opacity', 'gbox'),
                        'desc'    => __('Set your Youtube gallery item title background opacity.Opacity value 1 to 99', 'gbox'),
                        'type'    => 'number',
                        'default' => 75,

                    ),
                    array(
                        'name'    => 'you_title_color',
                        'label'   => __('Set title color', 'gbox'),
                        'desc'    => __('Set your Youtube gallery item text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'you_title_font',
                        'label'   => __('Set title font size', 'gbox'),
                        'desc'    => __('Default font size is 17px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'you_title_transform',
                        'label'   => __('Select title text transform', 'gbox'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('uppercase', 'gbox'),
                            'lowercase'  => __('Lowercase', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'you_title_padding',
                        'label'   => __('Set title padding', 'gbox'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'you_btn_font',
                        'label'   => __('Set button font size', 'gbox'),
                        'desc'    => __('Default font size 14px .', 'gbox'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'you_btn_color',
                        'label'   => __('Button text color', 'gbox'),
                        'desc'    => __('Set Youtube gallery item button text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'you_btn_border',
                        'label'   => __('Button border color', 'gbox'),
                        'desc'    => __('Set Youtube gallery item button border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'you_load_button',
                        'label'   => __('Load more button', 'gbox'),
                        'desc'    => __('Load more button is pro feature.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'pro'  => __('Only available in pro', 'gbox'),
                            'disable'  => __('Disable', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'you_item_number',
                        'label'   => __('Youtube video item ', 'gbox'),
                        'desc'    => __('Select how many item show in every page. pro feature.', 'gbox'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'you_load_position',
                        'label'   => __('Load more button position', 'gbox'),
                        'desc'    => __('Select load more button position left, right, center, full width. pro feature.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gbox'),
                            'right'  => __('Right', 'gbox'),
                            'center'  => __('Center', 'gbox'),
                            'full'  => __('Full width', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'you_load_color',
                        'label'   => __('Load more button color', 'gbox'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'you_load_bgcolor',
                        'label'   => __('Load more button background color. pro feature.', 'gbox'),
                        'desc'    => __('select more button background color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'you_load_color_hover',
                        'label'   => __('Load more button hover color', 'gbox'),
                        'desc'    => __('select more button color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'you_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color', 'gbox'),
                        'desc'    => __('select more button background color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),

                ),
                //vimeo style settings
                'vimeo_style' => array(
                    array(
                        'name'    => 'vimeo_column',
                        'label'   => __('Vimeo gallery column.', 'gbox'),
                        'desc'    => __('Set your Vimeo gallery Column. Some of the animation may not work properly in 4 column.', 'gbox'),
                        'type'   => 'select',
                        'default' => 3,
                        'options' => array(
                            2  => __('Two column', 'gbox'),
                            3  => __('Three column', 'gbox'),
                            4  => __('Four column', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'vimeo_autoplay',
                        'label'   => __('Vimeo video auto play.', 'gbox'),
                        'desc'    => __('You can set Vimeo video auto paly when open in lightbox.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'yes',
                        'options' => array(
                            'yes'  => __('Active', 'gbox'),
                            'no' => __('Hide', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'vimeo_border',
                        'label'   => __('vimeo column border', 'gbox'),
                        'desc'    => __('Set your vimeo border by px. default value 0', 'gbox'),
                        'type'     => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'vimeo_border_color',
                        'label'   => __('vimeo column border color', 'gbox'),
                        'desc'    => __('Set your vimeo border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'vimeo_border_type',
                        'label'   => __('vimeo column border type', 'gbox'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gbox'),
                        'type'              => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gbox'),
                            'dotted'  => __('Dotted', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'vimeo_animation',
                        'label'   => __('Select hover animation', 'gbox'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for vimeo video gallery.', 'gbox'),
                        'type'              => 'select',
                        'default' => 'ehover3',
                        'options'          => array(
                            'ehover1' => __('Animation One', 'cmb2'),
                            'ehover2'   => __('Animation Two', 'cmb2'),
                            'ehover3'     => __('Animation Three', 'cmb2'),
                            'ehover4'     => __('Animation Four', 'cmb2'),
                            'ehover5'     => __('Animation Five', 'cmb2'),

                        )
                    ),
                    array(
                        'name'    => 'vimeo_title_back',
                        'label'   => __('Title background color', 'gbox'),
                        'desc'    => __('Set your Vimeo gallery item title background color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'vimeo_title_opacity',
                        'label'   => __('Title background opacity', 'gbox'),
                        'desc'    => __('Set your Vimeo gallery item title background opacity.Opacity value 1 to 99', 'gbox'),
                        'type'   => 'number',
                        'default' => 50,

                    ),
                    array(
                        'name'    => 'vimeo_title_color',
                        'label'   => __('Set title color', 'gbox'),
                        'desc'    => __('Set your Vimeo gallery item text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'vimeo_title_font',
                        'label'   => __('Set title font size', 'gbox'),
                        'desc'    => __('Default font size is 17px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'vimeo_title_transform',
                        'label'   => __('Select title text transform', 'gbox'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('Uppercase', 'gbox'),
                            'lowercase'  => __('Lowercase', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'vimeo_title_padding',
                        'label'   => __('Set title padding', 'gbox'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'vimeo_btn_font',
                        'label'   => __('Set Button font size', 'gbox'),
                        'desc'    => __('Default font size 14px ', 'gbox'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'vimeo_btn_color',
                        'label'   => __('Button text color', 'gbox'),
                        'desc'    => __('Set vimeo gallery item button text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'vimeo_btn_border',
                        'label'   => __('Button border color', 'gbox'),
                        'desc'    => __('Set vimeo gallery item button border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'vimeo_load_button',
                        'label'   => __('Load more button', 'gbox'),
                        'desc'    => __('You can use load more button for pagination. pro feature', 'gbox'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'enable'  => __('Only available in pro', 'gbox'),
                            'disable'  => __('Disable', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'vimeo_item_number',
                        'label'   => __('Vimeo item number', 'gbox'),
                        'desc'    => __('Select how many item show in every page. pro feature', 'gbox'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'vimeo_load_position',
                        'label'   => __('Load more button position. pro feature.', 'gbox'),
                        'desc'    => __('Select load more button position left, right, center, full width.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gbox'),
                            'right'  => __('Right', 'gbox'),
                            'center'  => __('Center', 'gbox'),
                            'full'  => __('Full width', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'vimeo_load_color',
                        'label'   => __('Load more button color', 'gbox'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'vimeo_load_bgcolor',
                        'label'   => __('Load more button background color. pro feature', 'gbox'),
                        'desc'    => __('select more button background color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'vimeo_load_color_hover',
                        'label'   => __('Load more button hover color', 'gbox'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'vimeo_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color. pro feature.', 'gbox'),
                        'desc'    => __('select more button background color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),

                ),

                //iframe style settings
                'iframe_style' => array(
                    array(
                        'name'    => 'iframe_column',
                        'label'   => __('iframe gallery column.', 'gbox'),
                        'desc'    => __('Set your iframe gallery Column. Some of the animation may not work properly in 4 column.', 'gbox'),
                        'type'  => 'select',
                        'default' => 3,
                        'options' => array(
                            2  => __('Two column', 'gbox'),
                            3  => __('Three column', 'gbox'),
                            4  => __('Four column', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'iframe_border',
                        'label'   => __('iframe column border', 'gbox'),
                        'desc'    => __('Set your iframe border by px. default value 0', 'gbox'),
                        'type'              => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'iframe_border_color',
                        'label'   => __('iframe column border color', 'gbox'),
                        'desc'    => __('Set your iframe border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'iframe_border_type',
                        'label'   => __('iframe column border type', 'gbox'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gbox'),
                            'dotted'  => __('Dotted', 'gbox'),
                        )
                    ),
                    array(
                        'name'    => 'iframe_animation',
                        'label'   => __('Select hover animation', 'gbox'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for iframe gallery.', 'gbox'),
                        'type'              => 'select',
                        'default' => 'ehover12',
                        'options'          => array(
                            'ehover1' => __('Animation One', 'cmb2'),
                            'ehover2'   => __('Animation Two', 'cmb2'),
                            'ehover3'     => __('Animation Three', 'cmb2'),
                            'ehover4'     => __('Animation Four', 'cmb2'),
                            'ehover5'     => __('Animation Five', 'cmb2'),

                        )
                    ),
                    array(
                        'name'    => 'iframe_title_back',
                        'label'   => __('Title background color', 'gbox'),
                        'desc'    => __('Set your Soundcloud gallery item title background color.', 'gbox'),
                        'type'   => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'iframe_title_opacity',
                        'label'   => __('Title background opacity', 'gbox'),
                        'desc'    => __('Set your Soundcloud gallery item title background opacity.Opacity value 1 to 99', 'gbox'),
                        'type'    => 'number',
                        'default' => 75,

                    ),
                    array(
                        'name'    => 'iframe_title_color',
                        'label'   => __('Set title color', 'gbox'),
                        'desc'    => __('Set your Soundcloud gallery item text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'iframe_title_font',
                        'label'   => __('Set title font size', 'gbox'),
                        'desc'    => __('Default font size is 17px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'iframe_title_transform',
                        'label'   => __('Select title text transform', 'gbox'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gbox'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('Uppercase', 'gbox'),
                            'lowercase'  => __('Lowercase', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'iframe_title_padding',
                        'label'   => __('Set title padding', 'gbox'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'iframe_btn_font',
                        'label'   => __('Set Button font size', 'gbox'),
                        'desc'    => __('Default font size 14px.', 'gbox'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'iframe_btn_color',
                        'label'   => __('Button text color', 'gbox'),
                        'desc'    => __('Set iframe gallery item button text color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'iframe_btn_border',
                        'label'   => __('Button border color', 'gbox'),
                        'desc'    => __('Set iframe gallery item button border color.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'iframe_load_button',
                        'label'   => __('Load more button', 'gbox'),
                        'desc'    => __('You can use load more button for pagination. pro feature.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'enable'  => __('Only available in pro', 'gbox'),
                            'disable'  => __('Disable', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'iframe_item_number',
                        'label'   => __('Iframe item number', 'gbox'),
                        'desc'    => __('Select how many item show in every page. pro feature.', 'gbox'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'iframe_load_position',
                        'label'   => __('Load more button position', 'gbox'),
                        'desc'    => __('Select load more button position left, right, center, full width. pro feature.', 'gbox'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gbox'),
                            'right'  => __('Right', 'gbox'),
                            'center'  => __('Center', 'gbox'),
                            'full'  => __('Full width', 'gbox'),
                        )

                    ),
                    array(
                        'name'    => 'iframe_load_color',
                        'label'   => __('Load more button color', 'gbox'),
                        'desc'    => __('select more button color by this color option. pro feature', 'gbox'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'iframe_load_bgcolor',
                        'label'   => __('Load more button background color. pro feature', 'gbox'),
                        'desc'    => __('select more button background color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'iframe_load_color_hover',
                        'label'   => __('Load more button hover color', 'gbox'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'iframe_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color. pro feature', 'gbox'),
                        'desc'    => __('select more button background color by this color option.', 'gbox'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),



                ),

            );
            return $settings_fields;
        }
        function plugin_page()
        {
            echo '<div class="wrap easy-solution">';
            echo '<a href="http://wpthemespace.com/product/x-blog/" target="_blank"> <img src="https://wpthemespace.com/wp-content/uploads/2019/01/xblog-pro.png' . '" alt="X blog pro" /></a>';
            echo '<h1>' . esc_html__('Gallery box settings', 'gbox') . '</h1>';
            echo '<div class="welcome-panel">';
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();

            echo '</div>';
            echo '</div>';
        }

        /**
         * Get all the pages
         *
         * @return array page names with key value pairs
         */
        function get_pages()
        {
            $pages = get_pages();

            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[$page->ID] = $page->post_title;
                }
            }

            return $pages_options;
        }
    }
endif;
require plugin_dir_path(__FILE__) . '/src/class.settings-api.php';
new nGalleryBox_main_options();

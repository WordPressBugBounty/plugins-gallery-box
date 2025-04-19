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
                __('Gallery Box settings', 'gallery-box'),
                __('Gallery Box settings', 'gallery-box'),
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
                    'title' => __('Lightbox settings', 'gallery-box')
                ),
                array(
                    'id' => 'img_style',
                    'title' => __('All image gallery style', 'gallery-box')
                ),
                array(
                    'id' => 'youtube_style',
                    'title' => __('Youtube gallery style', 'gallery-box')
                ),
                array(
                    'id' => 'vimeo_style',
                    'title' => __('Vimeo gallery style', 'gallery-box')
                ),
                array(
                    'id' => 'iframe_style',
                    'title' => __('Iframe gallery style', 'gallery-box')
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
                        'label'   => __('Gallery Box font', 'gallery-box'),
                        'desc'    => __('You can use gallery box default font or use your theme font and typography.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'no',
                        'options' => array(
                            'yes' => __('Active', 'gallery-box'),
                            'no'  => __('Deactive', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'loader_style',
                        'label'   => __('Image preloader style', 'gallery-box'),
                        'desc'    => __('Select lightbox image preloader style.', 'gallery-box'),
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
                        'label'   => __('Set lightbox icon color.', 'gallery-box'),
                        'desc'    => __('The color show in arrow icon and close icon.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#b6b6b6',

                    ),
                    array(
                        'name'    => 'light_border',
                        'label'   => __('Lightbox Image border', 'gallery-box'),
                        'desc'    => __('Set your image border by px. default value 0', 'gallery-box'),
                        'type'              => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'light_bcolor',
                        'label'   => __('Set lightbox background color.', 'gallery-box'),
                        'desc'    => __('The color show in preloader, border and text background.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#d2d2d2',

                    ),

                    array(
                        'name'    => 'use_caption',
                        'label'   => __('lightbox caption', 'gallery-box'),
                        'desc'    => __('You can show hide lightbox caption.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Active', 'gallery-box'),
                            'No'  => __('Hide', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'cap_position',
                        'label'   => __('Lightbox caption position', 'gallery-box'),
                        'desc'    => __('Set gallery lightbox caption position.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'top' => __('top', 'gallery-box'),
                            'bottom'  => __('bottom', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'show_arrow',
                        'label'   => __('Image Gallery navigation', 'gallery-box'),
                        'desc'    => __('Gallery navigation only work in image gallery.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'yes',
                        'options' => array(
                            'yes' => __('Show', 'gallery-box'),
                            'no'  => __('Hide', 'gallery-box'),
                        )
                    ),

                ),
                //Image style
                'img_style' => array(
                    array(
                        'name'    => 'img_column',
                        'label'   => __('Image gallery column ', 'gallery-box'),
                        'desc'    => __('Set your image gallery Column. Some of the animation may not work properly in 4 column.', 'gallery-box'),
                        'type'              => 'select',
                        'default' => 3,
                        'options' => array(
                            1  => __('one column', 'gallery-box'),
                            2  => __('Two column', 'gallery-box'),
                            3  => __('Three column', 'gallery-box'),
                            4  => __('Four column', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'img_border',
                        'label'   => __('Image border', 'gallery-box'),
                        'desc'    => __('Set your image border by px. default value 0', 'gallery-box'),
                        'type'              => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'img_border_color',
                        'label'   => __('Image border color', 'gallery-box'),
                        'desc'    => __('Set your image border color.', 'gallery-box'),
                        'type'              => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'img_border_type',
                        'label'   => __('Image border type', 'gallery-box'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gallery-box'),
                            'dotted'  => __('Dotted', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'img_animation',
                        'label'   => __('Select hover animation', 'gallery-box'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for image gallery.', 'gallery-box'),
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
                        'label'   => __('Title background color', 'gallery-box'),
                        'desc'    => __('Set your image gallery item title background color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'img_title_opacity',
                        'label'   => __('Title background opacity', 'gallery-box'),
                        'desc'    => __('Set your image gallery item title background opacity.Opacity value 1 to 99', 'gallery-box'),
                        'type'              => 'number',
                        'default' => 50,

                    ),
                    array(
                        'name'    => 'img_title_color',
                        'label'   => __('Set title color', 'gallery-box'),
                        'desc'    => __('Set your image gallery item text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'img_title_font',
                        'label'   => __('Set title font size', 'gallery-box'),
                        'desc'    => __('Default font size is 17px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'img_title_transform',
                        'label'   => __('Select title text transform', 'gallery-box'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('Uppercase', 'gallery-box'),
                            'lowercase'  => __('Lowercase', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'img_title_padding',
                        'label'   => __('Set title padding', 'gallery-box'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'img_btn_font',
                        'label'   => __('Set Button font size', 'gallery-box'),
                        'desc'    => __('Default font size 14px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'img_btn_color',
                        'label'   => __('Button text color', 'gallery-box'),
                        'desc'    => __('Set Image gallery item button text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'img_btn_border',
                        'label'   => __('Button border color', 'gallery-box'),
                        'desc'    => __('Set Image gallery item button border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'img_load_button',
                        'label'   => __('Load more button', 'gallery-box'),
                        'desc'    => __('Load more button is pro feature.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'pro'  => __('Only available in pro', 'gallery-box'),
                            'disable'  => __('Disable', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'img_item_number',
                        'label'   => __('Images item number', 'gallery-box'),
                        'desc'    => __('Select how many item show in first page. pro feature', 'gallery-box'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'img_load_position',
                        'label'   => __('Load more button position', 'gallery-box'),
                        'desc'    => __('Select load more button position left, right, center, full width. pro feature', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gallery-box'),
                            'right'  => __('Right', 'gallery-box'),
                            'center'  => __('Center', 'gallery-box'),
                            'full'  => __('Full width', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'img_load_color',
                        'label'   => __('Load more button color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option. pro feature', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'img_load_bgcolor',
                        'label'   => __('Load more button background color.', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option. pro feature', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'img_load_color_hover',
                        'label'   => __('Load more button hover color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option.pro feature', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'img_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color. ', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option. pro feature', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),

                ),
                //Youtube style settings
                'youtube_style' => array(
                    array(
                        'name'    => 'youtube_column',
                        'label'   => __('Youtube gallery column.', 'gallery-box'),
                        'desc'    => __('Set your Youtube gallery Column. Some of the animation may not work properly in 4 column.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 3,
                        'options' => array(
                            2  => __('Two column', 'gallery-box'),
                            3  => __('Three column', 'gallery-box'),
                            4  => __('Four column', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'youtube_auto',
                        'label'   => __('Youtube video auto play.', 'gallery-box'),
                        'desc'    => __('You can set Youtube video auto paly when open in lightbox.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'yes',
                        'options' => array(
                            'yes'  => __('Active', 'gallery-box'),
                            'no' => __('Hide', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'youtube_border',
                        'label'   => __('youtube column border', 'gallery-box'),
                        'desc'    => __('Set your youtube border by px. default value 0', 'gallery-box'),
                        'type'   => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'youtube_border_color',
                        'label'   => __('youtube column border color', 'gallery-box'),
                        'desc'    => __('Set your youtube border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'youtube_border_type',
                        'label'   => __('youtube column border type', 'gallery-box'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gallery-box'),
                            'dotted'  => __('Dotted', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'you_animation',
                        'label'   => __('Select hover animation', 'gallery-box'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for Youtube video gallery.', 'gallery-box'),
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
                        'label'   => __('Title background color', 'gallery-box'),
                        'desc'    => __('Set your Youtube gallery item title background color.', 'gallery-box'),
                        'type'     => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'you_title_opacity',
                        'label'   => __('Title background opacity', 'gallery-box'),
                        'desc'    => __('Set your Youtube gallery item title background opacity.Opacity value 1 to 99', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 75,

                    ),
                    array(
                        'name'    => 'you_title_color',
                        'label'   => __('Set title color', 'gallery-box'),
                        'desc'    => __('Set your Youtube gallery item text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'you_title_font',
                        'label'   => __('Set title font size', 'gallery-box'),
                        'desc'    => __('Default font size is 17px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'you_title_transform',
                        'label'   => __('Select title text transform', 'gallery-box'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('uppercase', 'gallery-box'),
                            'lowercase'  => __('Lowercase', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'you_title_padding',
                        'label'   => __('Set title padding', 'gallery-box'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'you_btn_font',
                        'label'   => __('Set button font size', 'gallery-box'),
                        'desc'    => __('Default font size 14px .', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'you_btn_color',
                        'label'   => __('Button text color', 'gallery-box'),
                        'desc'    => __('Set Youtube gallery item button text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'you_btn_border',
                        'label'   => __('Button border color', 'gallery-box'),
                        'desc'    => __('Set Youtube gallery item button border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'you_load_button',
                        'label'   => __('Load more button', 'gallery-box'),
                        'desc'    => __('Load more button is pro feature.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'pro'  => __('Only available in pro', 'gallery-box'),
                            'disable'  => __('Disable', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'you_item_number',
                        'label'   => __('Youtube video item ', 'gallery-box'),
                        'desc'    => __('Select how many item show in every page. pro feature.', 'gallery-box'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'you_load_position',
                        'label'   => __('Load more button position', 'gallery-box'),
                        'desc'    => __('Select load more button position left, right, center, full width. pro feature.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gallery-box'),
                            'right'  => __('Right', 'gallery-box'),
                            'center'  => __('Center', 'gallery-box'),
                            'full'  => __('Full width', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'you_load_color',
                        'label'   => __('Load more button color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'you_load_bgcolor',
                        'label'   => __('Load more button background color. pro feature.', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'you_load_color_hover',
                        'label'   => __('Load more button hover color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'you_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),

                ),
                //vimeo style settings
                'vimeo_style' => array(
                    array(
                        'name'    => 'vimeo_column',
                        'label'   => __('Vimeo gallery column.', 'gallery-box'),
                        'desc'    => __('Set your Vimeo gallery Column. Some of the animation may not work properly in 4 column.', 'gallery-box'),
                        'type'   => 'select',
                        'default' => 3,
                        'options' => array(
                            2  => __('Two column', 'gallery-box'),
                            3  => __('Three column', 'gallery-box'),
                            4  => __('Four column', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'vimeo_autoplay',
                        'label'   => __('Vimeo video auto play.', 'gallery-box'),
                        'desc'    => __('You can set Vimeo video auto paly when open in lightbox.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'yes',
                        'options' => array(
                            'yes'  => __('Active', 'gallery-box'),
                            'no' => __('Hide', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'vimeo_border',
                        'label'   => __('vimeo column border', 'gallery-box'),
                        'desc'    => __('Set your vimeo border by px. default value 0', 'gallery-box'),
                        'type'     => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'vimeo_border_color',
                        'label'   => __('vimeo column border color', 'gallery-box'),
                        'desc'    => __('Set your vimeo border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'vimeo_border_type',
                        'label'   => __('vimeo column border type', 'gallery-box'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gallery-box'),
                        'type'              => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gallery-box'),
                            'dotted'  => __('Dotted', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'vimeo_animation',
                        'label'   => __('Select hover animation', 'gallery-box'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for vimeo video gallery.', 'gallery-box'),
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
                        'label'   => __('Title background color', 'gallery-box'),
                        'desc'    => __('Set your Vimeo gallery item title background color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'vimeo_title_opacity',
                        'label'   => __('Title background opacity', 'gallery-box'),
                        'desc'    => __('Set your Vimeo gallery item title background opacity.Opacity value 1 to 99', 'gallery-box'),
                        'type'   => 'number',
                        'default' => 50,

                    ),
                    array(
                        'name'    => 'vimeo_title_color',
                        'label'   => __('Set title color', 'gallery-box'),
                        'desc'    => __('Set your Vimeo gallery item text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'vimeo_title_font',
                        'label'   => __('Set title font size', 'gallery-box'),
                        'desc'    => __('Default font size is 17px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'vimeo_title_transform',
                        'label'   => __('Select title text transform', 'gallery-box'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('Uppercase', 'gallery-box'),
                            'lowercase'  => __('Lowercase', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'vimeo_title_padding',
                        'label'   => __('Set title padding', 'gallery-box'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'vimeo_btn_font',
                        'label'   => __('Set Button font size', 'gallery-box'),
                        'desc'    => __('Default font size 14px ', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'vimeo_btn_color',
                        'label'   => __('Button text color', 'gallery-box'),
                        'desc'    => __('Set vimeo gallery item button text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'vimeo_btn_border',
                        'label'   => __('Button border color', 'gallery-box'),
                        'desc'    => __('Set vimeo gallery item button border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'vimeo_load_button',
                        'label'   => __('Load more button', 'gallery-box'),
                        'desc'    => __('You can use load more button for pagination. pro feature', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'enable'  => __('Only available in pro', 'gallery-box'),
                            'disable'  => __('Disable', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'vimeo_item_number',
                        'label'   => __('Vimeo item number', 'gallery-box'),
                        'desc'    => __('Select how many item show in every page. pro feature', 'gallery-box'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'vimeo_load_position',
                        'label'   => __('Load more button position. pro feature.', 'gallery-box'),
                        'desc'    => __('Select load more button position left, right, center, full width.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gallery-box'),
                            'right'  => __('Right', 'gallery-box'),
                            'center'  => __('Center', 'gallery-box'),
                            'full'  => __('Full width', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'vimeo_load_color',
                        'label'   => __('Load more button color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'vimeo_load_bgcolor',
                        'label'   => __('Load more button background color. pro feature', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'vimeo_load_color_hover',
                        'label'   => __('Load more button hover color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'vimeo_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color. pro feature.', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#555555',
                    ),

                ),

                //iframe style settings
                'iframe_style' => array(
                    array(
                        'name'    => 'iframe_column',
                        'label'   => __('iframe gallery column.', 'gallery-box'),
                        'desc'    => __('Set your iframe gallery Column. Some of the animation may not work properly in 4 column.', 'gallery-box'),
                        'type'  => 'select',
                        'default' => 3,
                        'options' => array(
                            2  => __('Two column', 'gallery-box'),
                            3  => __('Three column', 'gallery-box'),
                            4  => __('Four column', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'iframe_border',
                        'label'   => __('iframe column border', 'gallery-box'),
                        'desc'    => __('Set your iframe border by px. default value 0', 'gallery-box'),
                        'type'              => 'number',
                        'default' => 0,

                    ),
                    array(
                        'name'    => 'iframe_border_color',
                        'label'   => __('iframe column border color', 'gallery-box'),
                        'desc'    => __('Set your iframe border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'iframe_border_type',
                        'label'   => __('iframe column border type', 'gallery-box'),
                        'desc'    => __('Dotted may not be seen,
					When the background color and border color same.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'solid',
                        'options' => array(
                            'solid'  => __('Solid', 'gallery-box'),
                            'dotted'  => __('Dotted', 'gallery-box'),
                        )
                    ),
                    array(
                        'name'    => 'iframe_animation',
                        'label'   => __('Select hover animation', 'gallery-box'),
                        'desc'    => __('This plugin pro version support 16 hover animation. select one for iframe gallery.', 'gallery-box'),
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
                        'label'   => __('Title background color', 'gallery-box'),
                        'desc'    => __('Set your Soundcloud gallery item title background color.', 'gallery-box'),
                        'type'   => 'color',
                        'default' => '#000000',

                    ),
                    array(
                        'name'    => 'iframe_title_opacity',
                        'label'   => __('Title background opacity', 'gallery-box'),
                        'desc'    => __('Set your Soundcloud gallery item title background opacity.Opacity value 1 to 99', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 75,

                    ),
                    array(
                        'name'    => 'iframe_title_color',
                        'label'   => __('Set title color', 'gallery-box'),
                        'desc'    => __('Set your Soundcloud gallery item text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'iframe_title_font',
                        'label'   => __('Set title font size', 'gallery-box'),
                        'desc'    => __('Default font size is 17px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 17,

                    ),
                    array(
                        'name'    => 'iframe_title_transform',
                        'label'   => __('Select title text transform', 'gallery-box'),
                        'desc'    => __('Set title text uppercase or lowercase.', 'gallery-box'),
                        'type'    => 'radio',
                        'default' => 'uppercase',
                        'options' => array(
                            'uppercase'  => __('Uppercase', 'gallery-box'),
                            'lowercase'  => __('Lowercase', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'iframe_title_padding',
                        'label'   => __('Set title padding', 'gallery-box'),
                        'desc'    => __('Set your title padding default padding is 10px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 20,

                    ),
                    array(
                        'name'    => 'iframe_btn_font',
                        'label'   => __('Set Button font size', 'gallery-box'),
                        'desc'    => __('Default font size 14px.', 'gallery-box'),
                        'type'    => 'number',
                        'default' => 14,

                    ),
                    array(
                        'name'    => 'iframe_btn_color',
                        'label'   => __('Button text color', 'gallery-box'),
                        'desc'    => __('Set iframe gallery item button text color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'iframe_btn_border',
                        'label'   => __('Button border color', 'gallery-box'),
                        'desc'    => __('Set iframe gallery item button border color.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',

                    ),
                    array(
                        'name'    => 'iframe_load_button',
                        'label'   => __('Load more button', 'gallery-box'),
                        'desc'    => __('You can use load more button for pagination. pro feature.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'disable',
                        'options' => array(
                            'enable'  => __('Only available in pro', 'gallery-box'),
                            'disable'  => __('Disable', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'iframe_item_number',
                        'label'   => __('Iframe item number', 'gallery-box'),
                        'desc'    => __('Select how many item show in every page. pro feature.', 'gallery-box'),
                        'type'   => 'number',
                        'default' => 10,

                    ),
                    array(
                        'name'    => 'iframe_load_position',
                        'label'   => __('Load more button position', 'gallery-box'),
                        'desc'    => __('Select load more button position left, right, center, full width. pro feature.', 'gallery-box'),
                        'type'    => 'select',
                        'default' => 'full',
                        'options' => array(
                            'left'  => __('Left', 'gallery-box'),
                            'right'  => __('Right', 'gallery-box'),
                            'center'  => __('Center', 'gallery-box'),
                            'full'  => __('Full width', 'gallery-box'),
                        )

                    ),
                    array(
                        'name'    => 'iframe_load_color',
                        'label'   => __('Load more button color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option. pro feature', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#000000',
                    ),
                    array(
                        'name'    => 'iframe_load_bgcolor',
                        'label'   => __('Load more button background color. pro feature', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#cccccc',
                    ),
                    array(
                        'name'    => 'iframe_load_color_hover',
                        'label'   => __('Load more button hover color', 'gallery-box'),
                        'desc'    => __('select more button color by this color option. pro feature.', 'gallery-box'),
                        'type'    => 'color',
                        'default' => '#ffffff',
                    ),
                    array(
                        'name'    => 'iframe_load_bgcolor_hover',
                        'label'   => __('Load more button hover background color. pro feature', 'gallery-box'),
                        'desc'    => __('select more button background color by this color option.', 'gallery-box'),
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
            echo '<h1>' . esc_html__('Gallery box settings', 'gallery-box') . '</h1>';
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

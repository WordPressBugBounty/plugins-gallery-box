<?php
/*
 * All metabox create here.
 *
 * @link              http://awesomebootstrap.net
 * @since             1.0.0
 * @package           Gallery box wordpress plugin
 */

//add group fields 

/**
 * Hook in and add a metabox to demonstrate grouped fields
 */
if (!function_exists('gbox_meta_group')) :
	function gbox_meta_group()
	{
		/**
		 *  Gallery box meta field groups
		 */
		$gallery_box_meta = new_cmb2_box(array(
			'id'           => 'm_gallery',
			'title'        => __('Choose gallery type and click to add item', 'gallery-box'),
			'object_types' => array('gallery_box',),
			'context'    => 'advanced',
			'priority'   => 'high',
			'closed'     => false,
			'tabs'      => array(

				'gbox_welcome' => array(
					'label' => __('Welcome tab', 'gallery-box'),
					// 'show_on_cb' => 'cmb2_tabs_show_if_front_page',
					'icon'  => 'dashicons-format-image', // Dashicon
				),
				'simg' => array(
					'label' => __('Quick imaegs Gallery', 'gallery-box'),
					// 'show_on_cb' => 'cmb2_tabs_show_if_front_page',
					'icon'  => 'dashicons-format-image', // Dashicon
				),
				'image' => array(
					'label' => __('Advance Image Gallery', 'gallery-box'),
					// 'show_on_cb' => 'cmb2_tabs_show_if_front_page',
					'icon'  => 'dashicons-images-alt2', // Dashicon
				),
				'portfolio' => array(
					'label' => __('Portfolio Gallery', 'gallery-box'),
					// 'show_on_cb' => 'cmb2_tabs_show_if_front_page',
					'icon'  => 'dashicons-portfolio', // Dashicon
				),
				'youtubee'  => array(
					'label' => __('Youtube gallery', 'gallery-box'),
					'icon'  => 'dashicons-video-alt2', // Dashicon
				),
				'vime'    => array(
					'label' => __('Vimeo gallery', 'gallery-box'),
					'icon'  => 'dashicons-video-alt', // Custom icon, using image
				),
				'iframe'    => array(
					'label' => __('iframe gallery', 'gallery-box'),
					'icon'  => 'dashicons-welcome-view-site', // Custom icon, using image
				),
				'tabgallery'    => array(
					'label' => __('Gallery Tab pro', 'gallery-box'),
					'icon'  => 'dashicons-admin-generic', // Custom icon, using image
				),
				'css'    => array(
					'label' => __('Custom css', 'gallery-box'),
					'icon'  => 'dashicons-admin-generic', // Custom icon, using image
				),
				'donation'    => array(
					'label' => __('Upgrade pro', 'gallery-box'),
					'icon'  => 'dashicons-visibility', // Custom icon, using image
				),
			),
		));

		/*
	* welcome tab
	*
	*/
		$gallery_box_meta->add_field(array(
			'name'       => '',
			'desc'       => __(
				' 
        <div class="gboxwelcome-text"> 
        <div class="gboxwel-img"> 
            <img src="' . plugin_dir_url(dirname(__FILE__)) . 'images/image-small.jpg' . '" alt="" />
        </div>
            <div class="gboxwel-text">
            <h1>' . __('Welcome Gallery Box', 'gallery-box') . '</h1>
            <a target="_blank" class="gbox-pro-link" href="https://wpthemespace.com/product/gallery-box-pro/?add-to-cart=688">' . __('Upgrade To Pro', 'gallery-box') . '</a>
            <h3>' . __('Select your gallery tab', 'gallery-box') . '</h3>
           <p>' . __('Only add gallery items in your selected gallery and ignor all other tab.', 'gallery-box') . '</p> 
            </div>
        </div>',
				'gallery-box'
			),
			'id'         => 'gbox_wel',
			'type'       => 'text',
			'tab'  => 'gbox_welcome',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),

		));



		/*
	* Simple image gallery
	*
	*/
		$gallery_box_meta->add_field(array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'simg_type',
			'type' => 'radio_image',
			'tab'  => 'simg',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
			'options' => array(
				'img_add' => __('Add gallery images', 'gallery-box'),
				'img_set'   => __('Quick gallery settings', 'gallery-box'),
			),
			'default' => 'img_add',

		));

		$gallery_box_meta->add_field(array(
			'name' => __('Add Gallery Images', 'gallery-box'),
			'desc' => __('You can add all gallery images by this options. It\'s quick and easy.', 'gallery-box'),
			'id'   => 'simple_imgs',
			'type' => 'file_list',
			'tab'  => 'simg',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
			'attributes' => array(
				'data-conditional-id' => 'simg_type',
				'data-conditional-value' => 'img_add',

			),

		));

		$simage_group_id = $gallery_box_meta->add_field(array(
			'id'          => 'simg_main',
			'type'        => 'group',
			'tab'  => 'simg',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Quick Image gallery settings', 'gallery-box'),
				'closed'     => false,
			),

		));


		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Image gallery layout', 'gallery-box'),
			'desc'             => __('Select image gallery layout masonry or fixed height.', 'gallery-box'),
			'id'               => 'simg_layout_type',
			'type'             => 'pw_select',
			'default'          => 'masonry_layout',
			'options'          => array(
				'normal_layout'   => __('Fixed height', 'gallery-box'),
				'masonry_layout'   => __('Masonry gallery layout style one ', 'gallery-box'),
				'pro1'   => __('Masonry gallery layout style two (Available in pro) )', 'gallery-box'),
				'pro2'   => __('Masonry gallery layout style three (Available in pro)', 'gallery-box'),
				'carousel_slider'   => __('Carousel slider', 'gallery-box'),
				'carousel_fixed'   => __('Carousel slider fixed height', 'gallery-box'),

			),

		));


		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Show carousel nav', 'gallery-box'),
			'desc'             => __('You can show or hide carousel nav.', 'gallery-box'),
			'id'               => 'simg_car_nav',
			'type'	           => 'switch',
			'default'          => '',
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('carousel_slider', 'carousel_fixed')),

			),

		));
		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Show carousel dot', 'gallery-box'),
			'desc'             => __('You can show or hide carousel dot.', 'gallery-box'),
			'id'               => 'simg_car_dot',
			'type'	           => 'switch',
			'default'          => '',
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('carousel_slider', 'carousel_fixed')),

			),

		));
		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Carousel auto play', 'gallery-box'),
			'desc'             => __('You can active or hide carousel autoplay.', 'gallery-box'),
			'id'               => 'simg_car_auto',
			'type'	           => 'switch',
			'default'          => '',
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('carousel_slider', 'carousel_fixed')),

			),

		));
		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Number of images', 'gallery-box'),
			'desc'             => __('Set imaegs for carosuel. You can set one image for create slider.', 'gallery-box'),
			'id'               => 'simg_car_imgnum',
			'type'        => 'own_slider',
			'min'         => '1',
			'max'         => '10',
			'default'     => '3', // start value
			'value_label' => __('images:', 'gallery-box'),
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('carousel_slider', 'carousel_fixed')),

			),

		));

		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Images height', 'gallery-box'),
			'desc'             => __('Set image height. Image height only work fixed height layout and fixed height carosuel.', 'gallery-box'),
			'id'               => 'simg_custom_height',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '1000',
			'default'     => '220', // start value
			'value_label' => __('px:', 'gallery-box'),
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('normal_layout', 'carousel_fixed')),

			),

		));


		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Image size', 'gallery-box'),
			'desc'             => __('Set image size. image size randomly set in the masonary image layout . ', 'gallery-box'),
			'id'               => 'simg_img_size',
			'type'             => 'pw_select',
			'default'          => 'gbox-medium',
			'options'          => array(
				'thumbnail' => __('Thumbnail ( 150px x 150px hard cropped )  ', 'gallery-box'),
				'medium'   => __(' Medium ( 300px x 300px hard cropped )  ', 'gallery-box'),
				'gbox-medium'   => __(' Extra medium ( 450px x 450px )  ', 'gallery-box'),
				'gbox-large'   => __(' Large medium ( 600px x 600px )  ', 'gallery-box'),
				'gbox-horizontal'   => __(' horizontal( 1000px x 500px )  ', 'gallery-box'),
				'gbox-hlarge'   => __(' Large horizontal ( 1400px x 600px )  ', 'gallery-box'),
				'gbox-vertical'   => __(' Vertical ( 600px x 900px )  ', 'gallery-box'),
				'large' => __('Large ( 1024px x 1024px max height 1024px )', 'gallery-box'),
				'full'     => __('Full (original size uploaded)', 'gallery-box'),

			),
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('carousel_slider', 'carousel_fixed', 'normal_layout')),

			),

		));
		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Image gallery column', 'gallery-box'),
			'desc'             => __('Set image gallery column for this image gallery. Image column not work in justify gallery.', 'gallery-box'),
			'id'               => 'simg_column',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default'  => __('Default', 'gallery-box'),
				1  => __('one column', 'gallery-box'),
				2  => __('Two column', 'gallery-box'),
				3  => __('Three column', 'gallery-box'),
				4  => __('Four column', 'gallery-box'),

			),
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('masonry_layout', 'masonry_layout_two', 'masonry_layout_three', 'normal_layout')),

			),

		));

		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Image gallery lightbox', 'gallery-box'),
			'desc'             => __('You may set lightbox only overlay or lihghtbox and link overlay or set image only gallery.', 'gallery-box'),
			'id'               => 'simg_lightbox',
			'type'             => 'pw_select',
			'default'          => 'light_show',
			'options'          => array(
				'light_show'   => __('Lightbox active', 'gallery-box'),
				'light_hide'   => __('Lightbox hide', 'gallery-box'),

			),

		));

		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Load more button', 'gallery-box'),
			'desc'             => __('Load more button only available in pro verison.', 'gallery-box'),
			'id'               => 'simg_loadmore',
			'type'             => 'pw_select',
			'default'          => 'pro1',
			'options'          => array(
				'pro1'   => __('Only available in pro', 'gallery-box'),
				'disable'  => __('Disable', 'gallery-box'),

			),
			'attributes' => array(
				'data-conditional-id' => 'simg_layout_type',
				'data-conditional-value' => wp_json_encode(array('masonry_layout', 'masonry_layout_two', 'masonry_layout_three', 'normal_layout')),

			),

		));

		$gallery_box_meta->add_group_field($simage_group_id, array(
			'name'             => __('Images margin', 'gallery-box'),
			'desc'             => __('Set image margin. The margin work in right and bottom position.', 'gallery-box'),
			'id'               => 'simg_img_margin',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '100',
			'default'     => '0', // start value
			'value_label' => __('px:', 'gallery-box'),


		));

		/*
	 * Advance Image all meta options set here.
	 */
		$gallery_box_meta->add_field(array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'adimg_type',
			'type' => 'radio_image',
			'tab'  => 'image',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
			'options' => array(
				'img_add' => __('Add advance images', 'gallery-box'),
				'img_set'   => __('Advance image gallery Settings', 'gallery-box'),
			),
			'default' => 'img_add',

		));
		$image_group_id = $gallery_box_meta->add_field(array(
			'id'          => 'img_main',
			'type'        => 'group',
			'tab'  => 'image',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
			'before_group'       => '<div id="gbox_adimg">',
			'after_group'        => '</div>',
			'options'     => array(
				'group_title'   => __('Advance Image gallery item {#}', 'gallery-box'),
				'sortable'          => true,
				'add_button'    => __('Add more', 'gallery-box'),
				'remove_button' => __('Remove Entry', 'gallery-box'),
			),
		));

		$gallery_box_meta->add_group_field($image_group_id, array(
			'name' => __('Enter gallery item title', 'gallery-box'),
			'desc' => __('Set Your gallery item title here.', 'gallery-box'),
			'id'   => 'image_title',
			'type' => 'text',
		));
		$gallery_box_meta->add_group_field($image_group_id, array(
			'name' => __('Set gallery Image', 'gallery-box'),
			'desc' => __('This image show in front.Big size image good for masonry image layout.', 'gallery-box'),
			'id'   => 'image_small',
			'type' => 'file',
		));
		$gallery_box_meta->add_group_field($image_group_id, array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'advance_options',
			'type' => 'radio_image',
			'options' => array(
				'show_adv' => __('Show advance options', 'gallery-box'),
				'hide_adv'   => __('Hide advance options', 'gallery-box'),
			),
			'default' => 'hide_adv',

		));

		$gallery_box_meta->add_group_field($image_group_id, array(
			'name' => __('Enter link url', 'gallery-box'),
			'desc' => __('Please type or past your link url here. The link only show link only gallery.', 'gallery-box'),
			'id'   => 'link_url',
			'type' => 'text_url',
			'attributes' => array(
				'data-conditional-id' => 'advance_options',
				'data-conditional-value' => 'show_adv',

			),

		));

		$gallery_box_meta->add_group_field($image_group_id, array(
			'name' => __('Set lightbox Image', 'gallery-box'),
			'desc' => __('This image show when lightbox open.If you don\'t set this image, primery gallery image will be open lightbox.', 'gallery-box'),
			'id'   => 'image_light',
			'type' => 'file',
			'attributes' => array(
				'data-conditional-id' => 'advance_options',
				'data-conditional-value' => 'show_adv',

			),

		));

		$gallery_box_meta->add_group_field($image_group_id, array(
			'name'       => __('Enter image lightbox caption', 'gallery-box'),
			'desc' 		=> __('Set your lightbox caption.You can hide or show caption by lightbox settings.Default caption is item title', 'gallery-box'),
			'id'         => 'img_caption',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'advance_options',
				'data-conditional-value' => 'show_adv',

			),

		));

		$gallery_box_meta->add_group_field($image_group_id, array(
			'name'       => __('Enter button text', 'gallery-box'),
			'desc' 		=> __('Button text must be small. <strong>Note:</strong> Button text only show lightbox only mode. Default button text is( view large ).', 'gallery-box'),
			'id'         => 'img_btn_text',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'advance_options',
				'data-conditional-value' => 'show_adv',

			),
		));

		// advance gallery settings
		$settings_group = $gallery_box_meta->add_field(array(
			'id'          => 'settings_main',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Gallery settings', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="settings_maintab">',
			'after_group'        => '</div>',
			'tab'  => 'image',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));


		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Image gallery type', 'gallery-box'),
			'desc'             => __('Select image gallery layout masonry or fixed height.', 'gallery-box'),
			'id'               => 'layout_type',
			'type'             => 'pw_select',
			'default'          => 'masonry',
			'options'          => array(
				'n_gallery'   => __('Fixed height gallery layout ', 'gallery-box'),
				'masonry'   => __('masonry layout style one', 'gallery-box'),
				'pro1'   => __('masonry layout style two (pro feature)', 'gallery-box'),
				'pro2'   => __('masonry layout style three (pro feature)', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Set images height', 'gallery-box'),
			'desc'             => __('Set image height by this settings. Image height only work fixed height gallery.', 'gallery-box'),
			'id'               => 'img_custom_height',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '1000',
			'default'     => '220', // start value
			'value_label' => __('px:', 'gallery-box'),
			'attributes' => array(
				'data-conditional-id' => 'layout_type',
				'data-conditional-value' => 'n_gallery',

			)

		));
		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Image size', 'gallery-box'),
			'desc'             => __('Set image size. image size randomly set in the masonary image layout . ', 'gallery-box'),
			'id'               => 'adimg_img_size',
			'type'             => 'pw_select',
			'default'          => 'gbox-medium',
			'options'          => array(
				'default' => __('Thumbnail ( 150px x 150px hard cropped )  ', 'gallery-box'),
				'thumbnail' => __('Thumbnail ( 150px x 150px hard cropped )  ', 'gallery-box'),
				'medium'   => __(' Medium ( 300px x 300px hard cropped )  ', 'gallery-box'),
				'gbox-medium'   => __(' Extra medium ( 450px x 450px )  ', 'gallery-box'),
				'gbox-large'   => __(' Large medium ( 600px x 600px )  ', 'gallery-box'),
				'gbox-horizontal'   => __(' horizontal( 1000px x 500px )  ', 'gallery-box'),
				'gbox-hlarge'   => __(' Large horizontal ( 1400px x 600px )  ', 'gallery-box'),
				'gbox-vertical'   => __(' Vertical ( 600px x 900px )  ', 'gallery-box'),
				'large' => __('Large ( 1024px x 1024px max height 1024px )', 'gallery-box'),
				'full'     => __('Full (original size uploaded)', 'gallery-box'),

			),
			'attributes' => array(
				'data-conditional-id' => 'layout_type',
				'data-conditional-value' => wp_json_encode(array('masonry_layout', 'n_gallery')),

			)

		));
		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Image gallery column', 'gallery-box'),
			'desc'             => __('Set image gallery column for this image gallery.', 'gallery-box'),
			'id'               => 'uni_img_column',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default'  => __('Default', 'gallery-box'),
				1  => __('one column', 'gallery-box'),
				2  => __('Two column', 'gallery-box'),
				3  => __('Three column', 'gallery-box'),
				4  => __('Four column', 'gallery-box'),

			),

		));
		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Image gallery overlay', 'gallery-box'),
			'desc'             => __('You may set lightbox only overlay or title only or link overlay or set image only gallery.', 'gallery-box'),
			'id'               => 'gbox_img_link_type',
			'type'             => 'pw_select',
			'default'          => 'light',
			'options'          => array(
				'light'   => __('Lightbox only', 'gallery-box'),
				'link_only'   => __('Link only', 'gallery-box'),
				'tit_only'   => __('Title only gallery', 'gallery-box'),
				'img_only'   => __('Image only gallery', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Gallery items order', 'gallery-box'),
			'desc'             => __('You can set order ascending or descending.', 'gallery-box'),
			'id'               => 'gbox_img_order',
			'type'             => 'pw_select',
			'default'          => 'asc',
			'options'          => array(
				'asc'   => __('Ascending order', 'gallery-box'),
				'desc'   => __('Descending order', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Select hover animation', 'gallery-box'),
			'desc'             => __('This hover animation only for this image gallery. This animation not work in image only gallery. <span style="color:red">Support 16 animation in pro version.</span>', 'gallery-box'),
			'id'               => 'uni_img_hover',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default' => __('Default', 'gallery-box'),
				'ehover1' => __('Animation One', 'gallery-box'),
				'ehover2'   => __('Animation Two', 'gallery-box'),
				'ehover3'     => __('Animation Three', 'gallery-box'),
				'ehover4'     => __('Animation Four', 'gallery-box'),
				'ehover5'     => __('Animation Five', 'gallery-box'),

			),

		));

		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Item margin', 'gallery-box'),
			'desc'             => __('The margin work right and bottom. The margin set by px.', 'gallery-box'),
			'id'               => 'img_right_margin',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '50',
			'default'     => '0', // start value
			'value_label' => __('px:', 'gallery-box'),

		));

		$gallery_box_meta->add_group_field($settings_group, array(
			'name'             => __('Load more button', 'gallery-box'),
			'desc'             => __('Load more button only available in pro verison.', 'gallery-box'),
			'id'               => 'uniqe_loadmore',
			'type'             => 'pw_select',
			'default'          => 'pro1',
			'options'          => array(
				'pro1'   => __('Only available in pro', 'gallery-box'),
				'disable'  => __('Disable', 'gallery-box'),

			),
		));


		/*
	 * portfolio gallery meta.
	 */
		$gallery_box_meta->add_field(array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'portfolio_type',
			'type' => 'radio_image',
			'tab'  => 'portfolio',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
			'options' => array(
				'img_add' => __('Add portfolios', 'gallery-box'),
				'img_set'   => __('Portfolio gallery Settings', 'gallery-box'),
			),
			'default' => 'img_add',

		));
		$portfolio_group_id = $gallery_box_meta->add_field(array(
			'id'          => 'portfo_main',
			'type'        => 'group',
			'tab'  => 'portfolio',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
			'before_group'       => '<div id="gbox_portfolio">',
			'after_group'        => '</div>',
			'options'     => array(
				'group_title'   => __('Portfolio gallery item {#}', 'gallery-box'),
				'add_button'    => __('Add more', 'gallery-box'),
				'remove_button' => __('Remove Entry', 'gallery-box'),
				'sortable'          => true,
			),
		));

		$gallery_box_meta->add_group_field($portfolio_group_id, array(
			'name' => __('Enter portfolio title', 'gallery-box'),
			'desc' => __('Portfolio item title write here.', 'gallery-box'),
			'id'   => 'portfolio_title',
			'type' => 'text',
		));
		$gallery_box_meta->add_group_field($portfolio_group_id, array(
			'name' => __('Set Portfolio Image', 'gallery-box'),
			'desc' => __('Set your portfolio image here.', 'gallery-box'),
			'id'   => 'port_img',
			'type' => 'file',
		));
		$gallery_box_meta->add_group_field($portfolio_group_id, array(
			'name' => __('Enter portfolio link', 'gallery-box'),
			'desc' => __('Enter your portfolio link here.', 'gallery-box'),
			'id'   => 'port_link',
			'type' => 'text_url',

		));
		$gallery_box_meta->add_group_field($portfolio_group_id, array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'advance_options',
			'type' => 'radio_image',
			'options' => array(
				'show_adv' => __('Show advance options', 'gallery-box'),
				'hide_adv'   => __('Hide advance options', 'gallery-box'),
			),
			'default' => 'hide_adv',

		));



		$gallery_box_meta->add_group_field($portfolio_group_id, array(
			'name' => __('Set lightbox Image', 'gallery-box'),
			'desc' => __('This image show when lightbox open.If you don\'t set this image, gallery image will be open lightbox.', 'gallery-box'),
			'id'   => 'image_light',
			'type' => 'file',
			'attributes' => array(
				'data-conditional-id' => 'advance_options',
				'data-conditional-value' => 'show_adv',

			),

		));

		$gallery_box_meta->add_group_field($portfolio_group_id, array(
			'name'       => __('Enter image lightbox caption', 'gallery-box'),
			'desc' 		=> __('Set your lightbox caption.You can hide or show caption by lightbox settings.Default caption is item title', 'gallery-box'),
			'id'         => 'port_caption',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'advance_options',
				'data-conditional-value' => 'show_adv',

			),

		));


		// advance gallery settings
		$port_settings_group = $gallery_box_meta->add_field(array(
			'id'          => 'port_settings',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Portfolio gallery settings', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="port_settings">',
			'after_group'        => '</div>',
			'tab'  => 'portfolio',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));


		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Portfolio gallery type', 'gallery-box'),
			'desc'             => __('Select image gallery layout masonry or fixed height.', 'gallery-box'),
			'id'               => 'layout_type',
			'type'             => 'pw_select',
			'default'          => 'masonry',
			'options'          => array(
				'fixed'   => __('Fixed height gallery layout ', 'gallery-box'),
				'masonry'   => __('masonry layout style one', 'gallery-box'),
				'pro1'   => __('masonry layout style two (pro feature)', 'gallery-box'),
				'pro2'   => __('masonry layout style three (pro feature)', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Set images height', 'gallery-box'),
			'desc'             => __('Set your image height. The height only work when you set fixed image.', 'gallery-box'),
			'id'               => 'img_custom_height',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '1000',
			'default'     => '220', // start value
			'value_label' => __('px:', 'gallery-box'),
			'attributes' => array(
				'data-conditional-id' => 'layout_type',
				'data-conditional-value' => 'fixed',

			)

		));
		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Portfolio Image size', 'gallery-box'),
			'desc'             => __('Set Portfolio image size. image size randomly set in the masonary image layout. ', 'gallery-box'),
			'id'               => 'port_img_size',
			'type'             => 'pw_select',
			'default'          => 'gbox-medium',
			'options'          => array(
				'default' => __('Thumbnail ( 150px x 150px hard cropped )  ', 'gallery-box'),
				'thumbnail' => __('Thumbnail ( 150px x 150px hard cropped )  ', 'gallery-box'),
				'medium'   => __(' Medium ( 300px x 300px hard cropped )  ', 'gallery-box'),
				'gbox-medium'   => __(' Extra medium ( 450px x 450px )  ', 'gallery-box'),
				'gbox-large'   => __(' Large medium ( 600px x 600px )  ', 'gallery-box'),
				'gbox-horizontal'   => __(' horizontal( 1000px x 500px )  ', 'gallery-box'),
				'gbox-hlarge'   => __(' Large horizontal ( 1400px x 600px )  ', 'gallery-box'),
				'gbox-vertical'   => __(' Vertical ( 600px x 900px )  ', 'gallery-box'),
				'large' => __('Large ( 1024px x 1024px max height 1024px )', 'gallery-box'),
				'full'     => __('Full (original size uploaded)', 'gallery-box'),

			),
			'attributes' => array(
				'data-conditional-id' => 'layout_type',
				'data-conditional-value' => wp_json_encode(array('masonry_layout', 'fixed')),

			)

		));
		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Portfolio image gallery column', 'gallery-box'),
			'desc'             => __('Set image gallery column for this image gallery.', 'gallery-box'),
			'id'               => 'uni_img_column',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default'  => __('Default', 'gallery-box'),
				1  => __('one column', 'gallery-box'),
				2  => __('Two column', 'gallery-box'),
				3  => __('Three column', 'gallery-box'),
				4  => __('Four column', 'gallery-box'),

			),

		));

		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Select hover animation', 'gallery-box'),
			'desc'             => __('This hover animation only for this image gallery. <span style="color:red">Support 16 animation in pro version.</span> ', 'gallery-box'),
			'id'               => 'uni_img_hover',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default' => __('Default', 'gallery-box'),
				'ehover1' => __('Animation One', 'gallery-box'),
				'ehover2'   => __('Animation Two', 'gallery-box'),
				'ehover3'     => __('Animation Three', 'gallery-box'),
				'ehover4'     => __('Animation Four', 'gallery-box'),
				'ehover5'     => __('Animation Five', 'gallery-box'),

			),

		));

		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Gallery items order', 'gallery-box'),
			'desc'             => __('You can set order ascending or descending.', 'gallery-box'),
			'id'               => 'gbox_portfolio_order',
			'type'             => 'pw_select',
			'default'          => 'asc',
			'options'          => array(
				'asc'   => __('Ascending order', 'gallery-box'),
				'desc'   => __('Descending order', 'gallery-box'),

			),
		));

		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Portfolio link open', 'gallery-box'),
			'desc'             => __('You can portfolio link self tab or new tab.', 'gallery-box'),
			'id'               => 'gbox_portfolio_link_tergat',
			'type'             => 'pw_select',
			'default'          => '_blank',
			'options'          => array(
				'_self'   => __('Self Tab', 'gallery-box'),
				'_blank'   => __('New Tab', 'gallery-box'),

			),
		));

		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Item margin', 'gallery-box'),
			'desc'             => __('The margin work right and bottom. The margin set by px.', 'gallery-box'),
			'id'               => 'img_right_margin',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '50',
			'default'     => '0', // start value
			'value_label' => __('px:', 'gallery-box'),

		));

		$gallery_box_meta->add_group_field($port_settings_group, array(
			'name'             => __('Load more button', 'gallery-box'),
			'desc'             => __('Load more button only available in pro verison.', 'gallery-box'),
			'id'               => 'uniqe_loadmore',
			'type'             => 'pw_select',
			'default'          => 'pro1',
			'options'          => array(
				'pro1'   => __('Only available in pro', 'gallery-box'),
				'disable'  => __('Disable', 'gallery-box'),

			),
		));



		/*
	 *Youtube all options meta set here.
	 */
		$gallery_box_meta->add_field(array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'youtube_type',
			'type' => 'radio_image',
			'tab'  => 'youtube',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
			'options' => array(
				'you_add' => __('Add Youtube gallery video', 'gallery-box'),
				'you_set'   => __('Youtube gallery settings', 'gallery-box'),
			),
			'default' => 'you_add',

		));
		$Youtube_group_id = $gallery_box_meta->add_field(array(
			'id'          => 'youtube_main',
			'type'        => 'group',
			'options'     => array(
				'group_title'   => __('Video gallery item  {#}', 'gallery-box'),
				'add_button'    => __('Add more', 'gallery-box'),
				'remove_button' =>  __('Remove', 'gallery-box'),
				'closed'     => false,
				'sortable'      => true,
			),
			'before_group'       => '<div id="youtube_maintab">',
			'after_group'        => '</div>',
			'tab'  => 'youtube',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));

		$gallery_box_meta->add_group_field($Youtube_group_id, array(
			'name' => __('Enter Gallery item title', 'gallery-box'),
			'desc' => __('Youtube Gallery item title enter here. ', 'gallery-box'),
			'id'   => 'you_title',
			'type' => 'text',
			'allow' => array('url', 'attachment')

		));

		$gallery_box_meta->add_group_field($Youtube_group_id, array(
			'name'       => __('Youtube video link', 'gallery-box'),
			'desc' 		=> __('Past or type Youtube video link. Go your Youtube url and copy link and past in this box.', 'gallery-box'),
			'id'         => 'you_url',
			'type'       => 'oembed',
		));

		$gallery_box_meta->add_group_field($Youtube_group_id, array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'you_advance',
			'type' => 'radio_image',
			'options' => array(
				'show_adv' => __('Show advance options', 'gallery-box'),
				'hide_adv'   => __('Hide advance options', 'gallery-box'),
			),
			'default' => 'hide_adv',

		));

		$gallery_box_meta->add_group_field($Youtube_group_id, array(
			'name' => __('Set Youtube gallery image', 'gallery-box'),
			'desc' => __('(optional) This image show in front. Default image is ( Youtube default image)', 'gallery-box'),
			'id'   => 'you_image',
			'type' => 'file',
			'allow' => array('url', 'attachment'),
			'attributes' => array(
				'data-conditional-id' => 'you_advance',
				'data-conditional-value' => 'show_adv',

			),

		));

		$gallery_box_meta->add_group_field($Youtube_group_id, array(
			'name'       => __('Enter video lightbox caption', 'gallery-box'),
			'desc' 		=> __('Set your lightbox caption.You can hide or show caption by lightbox settings.Default caption is item title', 'gallery-box'),
			'id'         => 'You_caption',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'you_advance',
				'data-conditional-value' => 'show_adv',

			),
		));

		$gallery_box_meta->add_group_field($Youtube_group_id, array(
			'name'       => __('Enter button text', 'gallery-box'),
			'desc' 		=> __('Button text must be small.Default button text is( Show video )', 'gallery-box'),
			'id'         => 'youtube_button',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'you_advance',
				'data-conditional-value' => 'show_adv',

			),
		));

		//Youtube gallery settings
		$you_settings = $gallery_box_meta->add_field(array(
			'id'          => 'you_settings',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Youtube gallery settings', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="you_settings">',
			'after_group'        => '</div>',
			'tab'  => 'youtube',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));


		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Youtube gallery layout', 'gallery-box'),
			'desc'             => __('Select image gallery layout masonry or fixed height.', 'gallery-box'),
			'id'               => 'you_layout',
			'type'             => 'pw_select',
			'default'          => 'masonry',
			'options'          => array(
				'fixed'   => __('Fixed height gallery layout ', 'gallery-box'),
				'masonry'   => __('Masonry layout style', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Set Youtube thumbnail height', 'gallery-box'),
			'desc'             => __('Set Youtube video thumbnail height.', 'gallery-box'),
			'id'               => 'thumb_height',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '1000',
			'default'     => '220', // start value
			'value_label' => __('px:', 'gallery-box'),
			'attributes' => array(
				'data-conditional-id' => 'you_layout',
				'data-conditional-value' => 'fixed',

			)

		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Video thumbnail size', 'gallery-box'),
			'desc'             => __('Set image size. The image size only work in custom video thumbnail. ', 'gallery-box'),
			'id'               => 'you_thumb_size',
			'type'             => 'pw_select',
			'default'          => 'gbox-medium',
			'options'          => array(
				'medium'   => __(' Medium ( 300px x 300px hard cropped )  ', 'gallery-box'),
				'gbox-medium'   => __(' Extra medium ( 450px x 450px )  ', 'gallery-box'),
				'gbox-large'   => __(' Large medium ( 600px x 600px )  ', 'gallery-box'),
				'gbox-horizontal'   => __(' horizontal( 1000px x 500px )  ', 'gallery-box'),
				'gbox-hlarge'   => __(' Large horizontal ( 1400px x 600px )  ', 'gallery-box'),
				'gbox-vertical'   => __(' Vertical ( 600px x 900px )  ', 'gallery-box'),

			),


		));

		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Video autoplay', 'gallery-box'),
			'desc'             => __('You can show or hide active or hide autoplay.', 'gallery-box'),
			'id'               => 'youtube_auto',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default'  => __('Default', 'gallery-box'),
				'yes' => __('Active', 'gallery-box'),
				'no'  => __('Hide', 'gallery-box'),

			),

		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Show video icon', 'gallery-box'),
			'desc'             => __('You can show or hide video play icon.', 'gallery-box'),
			'id'               => 'video_icon',
			'type'             => 'pw_select',
			'default'          => 'show',
			'options'          => array(
				'show' => __('Show', 'gallery-box'),
				'hide'  => __('Hide', 'gallery-box'),

			),

		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Youtube gallery column', 'gallery-box'),
			'desc'             => __('Set Youtbe gallery column for this Youtbe gallery.', 'gallery-box'),
			'id'               => 'you_column',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default'  => __('Default', 'gallery-box'),
				1  => __('one column', 'gallery-box'),
				2  => __('Two column', 'gallery-box'),
				3  => __('Three column', 'gallery-box'),
				4  => __('Four column', 'gallery-box'),

			),

		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Select hover animation', 'gallery-box'),
			'desc'             => __('This hover animation only for this Youtube gallery. <span style="color:red">Support 16 animation in pro version.</span>', 'gallery-box'),
			'id'               => 'you_hover',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default' => __('Default', 'gallery-box'),
				'ehover1' => __('Animation One', 'gallery-box'),
				'ehover2'   => __('Animation Two', 'gallery-box'),
				'ehover3'     => __('Animation Three', 'gallery-box'),
				'ehover4'     => __('Animation Four', 'gallery-box'),
				'ehover5'     => __('Animation Five', 'gallery-box'),

			),

		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Gallery items order', 'gallery-box'),
			'desc'             => __('You can set order ascending or descending.', 'gallery-box'),
			'id'               => 'gbox_you_order',
			'type'             => 'pw_select',
			'default'          => 'asc',
			'options'          => array(
				'asc'   => __('Ascending order', 'gallery-box'),
				'desc'   => __('Descending order', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Item margin', 'gallery-box'),
			'desc'             => __('The margin work right and bottom. The margin set by px.', 'gallery-box'),
			'id'               => 'you_margin',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '50',
			'default'     => '0', // start value
			'value_label' => __('px:', 'gallery-box'),

		));

		$gallery_box_meta->add_group_field($you_settings, array(
			'name'             => __('Load more button for this gallery', 'gallery-box'),
			'desc'             => __('Load more button only available in pro verison.', 'gallery-box'),
			'id'               => 'you_loadmore',
			'type'             => 'pw_select',
			'default'          => 'pro1',
			'options'          => array(
				'pro1'   => __('Only available in pro', 'gallery-box'),
				'disable'  => __('Disable', 'gallery-box'),

			),
		));



		/*
	 * Iframe gallery  meta options.
	 */
		$gallery_box_meta->add_field(array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'iframe_type',
			'type' => 'radio_image',
			'tab'  => 'iframe',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
			'options' => array(
				'iframe_add' => __('Add iframe gallery url', 'gallery-box'),
				'iframe_set'   => __('iframe gallery settings', 'gallery-box'),
			),
			'default' => 'iframe_add',

		));
		$iframe_group_id = $gallery_box_meta->add_field(array(
			'id'          => 'iframe_main',
			'type'        => 'group',
			'options'     => array(
				'group_title'   => __('iframe gallery item  {#}', 'gallery-box'), // since
				'add_button'    => __('Add more', 'gallery-box'),
				'remove_button' =>  __('Remove', 'gallery-box'),
				'closed'     => false,
				'sortable'      => true,
			),
			'before_group'       => '<div id="iframe_maintab">',
			'after_group'        => '</div>',
			'tab'  => 'iframe',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));
		$gallery_box_meta->add_group_field($iframe_group_id, array(
			'name' => __('Enter gallery item title', 'gallery-box'),
			'desc' => __('Iframe gallery item title enter here.', 'gallery-box'),
			'id'   => 'iframe_title',
			'type' => 'text',
		));
		$gallery_box_meta->add_group_field($iframe_group_id, array(
			'name' => __('Set Iframe gallery image', 'gallery-box'),
			'desc' => __('This image show in front.Image size (300*300) for 4 column,(450*450) for 3 column, (600*600) for 2 column.All images use same size for better view.', 'gallery-box'),
			'id'   => 'Iframe_image',
			'type' => 'file',
		));
		$gallery_box_meta->add_group_field($iframe_group_id, array(
			'name'       => __('Enter page url', 'gallery-box'),
			'desc'        =>  __('Copy your webpage url and past this box.', 'gallery-box'),
			'id'         => 'iframe_url',
			'type'       => 'text_url',
		));
		$gallery_box_meta->add_group_field($iframe_group_id, array(
			'name' => esc_html__('', 'gallery-box'),
			'id'   => 'iframe_advance',
			'type' => 'radio_image',
			'options' => array(
				'show_adv' => __('Show advance options', 'gallery-box'),
				'hide_adv'   => __('Hide advance options', 'gallery-box'),
			),
			'default' => 'hide_adv',

		));
		$gallery_box_meta->add_group_field($iframe_group_id, array(
			'name'       => __('Enter lightbox iframe caption', 'gallery-box'),
			'desc' => __('Set your lightbox caption.You can hide or show caption by lightbox settings.Default caption is item title', 'gallery-box'),
			'id'         => 'iframe_caption',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'iframe_advance',
				'data-conditional-value' => 'show_adv',

			),
		));

		$gallery_box_meta->add_group_field($iframe_group_id, array(
			'name'       => __('Enter button text', 'gallery-box'),
			'desc' => __('Button text must be small.Default button text is( SHOW IFRAME )', 'gallery-box'),
			'id'         => 'iframe_button',
			'type'       => 'text',
			'attributes' => array(
				'data-conditional-id' => 'iframe_advance',
				'data-conditional-value' => 'show_adv',

			),
		));

		//iframe gallery settings
		$iframe_settings = $gallery_box_meta->add_field(array(
			'id'          => 'iframe_settings',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('iframe gallery settings', 'gallery-box'),
				'closed'     => false,
			),
			'before_group'       => '<div id="iframe_settings">',
			'after_group'        => '</div>',
			'tab'  => 'iframe',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));


		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('iframe gallery layout', 'gallery-box'),
			'desc'             => __('Select iframe gallery layout masonry or fixed height.', 'gallery-box'),
			'id'               => 'iframe_layout',
			'type'             => 'pw_select',
			'default'          => 'masonry',
			'options'          => array(
				'fixed'   => __('Fixed height gallery layout ', 'gallery-box'),
				'masonry'   => __('Masonry layout', 'gallery-box'),

			),
		));
		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('Set iframe thumbnail height', 'gallery-box'),
			'desc'             => __('Set iframe video thumbnail height.', 'gallery-box'),
			'id'               => 'iframe_height',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '1000',
			'default'     => '220', // start value
			'value_label' => __('px:', 'gallery-box'),
			'attributes' => array(
				'data-conditional-id' => 'iframe_layout',
				'data-conditional-value' => 'fixed',

			)

		));
		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('iframe thumbnail size', 'gallery-box'),
			'desc'             => __('Set image size. image size set for iframe gallery. ', 'gallery-box'),
			'id'               => 'iframe_thumb_size',
			'type'             => 'pw_select',
			'default'          => 'gbox-medium',
			'options'          => array(
				'medium'   => __(' Medium ( 300px x 300px hard cropped )  ', 'gallery-box'),
				'gbox-medium'   => __(' Extra medium ( 450px x 450px )  ', 'gallery-box'),
				'gbox-large'   => __(' Large medium ( 600px x 600px )  ', 'gallery-box'),
				'gbox-horizontal'   => __(' horizontal( 1000px x 500px )  ', 'gallery-box'),
				'gbox-hlarge'   => __(' Large horizontal ( 1400px x 600px )  ', 'gallery-box'),
				'gbox-vertical'   => __(' Vertical ( 600px x 900px )  ', 'gallery-box'),

			),


		));

		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('Vimeo gallery column', 'gallery-box'),
			'desc'             => __('Set iframe gallery column for this iframe gallery.', 'gallery-box'),
			'id'               => 'iframe_column',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default'  => __('Default', 'gallery-box'),
				1  => __('one column', 'gallery-box'),
				2  => __('Two column', 'gallery-box'),
				3  => __('Three column', 'gallery-box'),
				4  => __('Four column', 'gallery-box'),

			),

		));


		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('Select hover animation', 'gallery-box'),
			'desc'             => __('This hover animation only for this iframe gallery. <span style="color:red">Support 16 animation in pro version.</span>', 'gallery-box'),
			'id'               => 'iframe_hover',
			'type'             => 'pw_select',
			'default'          => 'default',
			'options'          => array(
				'default' => __('Default', 'gallery-box'),
				'ehover1' => __('Animation One', 'gallery-box'),
				'ehover2'   => __('Animation Two', 'gallery-box'),
				'ehover3'     => __('Animation Three', 'gallery-box'),
				'ehover4'     => __('Animation Four', 'gallery-box'),
				'ehover5'     => __('Animation Five', 'gallery-box'),

			),

		));
		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('Gallery items order', 'gallery-box'),
			'desc'             => __('You can set order ascending or descending.', 'gallery-box'),
			'id'               => 'gbox_iframe_order',
			'type'             => 'pw_select',
			'default'          => 'asc',
			'options'          => array(
				'asc'   => __('Ascending order', 'gallery-box'),
				'desc'   => __('Descending order', 'gallery-box'),

			),
		));

		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('Item margin', 'gallery-box'),
			'desc'             => __('The margin work right and bottom. The margin set by px.', 'gallery-box'),
			'id'               => 'iframe_margin',
			'type'        => 'own_slider',
			'min'         => '0',
			'max'         => '50',
			'default'     => '0', // start value
			'value_label' => __('px:', 'gallery-box'),

		));

		$gallery_box_meta->add_group_field($iframe_settings, array(
			'name'             => __('Load more button for this gallery', 'gallery-box'),
			'desc'             => __('Load more button only available in pro verison.', 'gallery-box'),
			'id'               => 'iframe_loadmore',
			'type'             => 'pw_select',
			'default'          => 'pro1',
			'options'          => array(
				'pro1'   => __('Only available in pro', 'gallery-box'),
				'disable'  => __('Disable', 'gallery-box'),

			),
		));

		/*
	 * Custom css field.
	 */

		$gallery_box_meta->add_field(array(
			'name'             => __('Custom css', 'gallery-box'),
			'desc'             => __('Enter your custom css code here.This css code only for this gallery.', 'gallery-box'),
			'id'               => 'custom_css',
			'type'             => 'textarea_code',
			'default'          => '',
			'tab'  => 'css',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),


		));

		$yout_gallery = $gallery_box_meta->add_field(array(
			'id'          => 'youtu_gallery',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Youtube Gallery', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="tab_gallery">',
			'after_group'        => '</div>',
			'tab'  => 'youtubee',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));
		$gallery_box_meta->add_group_field($yout_gallery, array(
			'name'       => '',
			'desc'       => __(
				' 
        <div class="gboxhelp-text"> 
        	<a href="https://wpthemespace.com/product/gallery-box-pro/?add-to-cart=688" target="_blank">
            <img src="' . plugin_dir_url(dirname(__FILE__)) . 'images/gallery-youtube.png' . '" alt="pro image" />
            </a>
            <a href="' . esc_url('https://gbox.wpteamx.com/youtube-video-gallery/') . '" target="_blank" class="gbox-demo-link">Show youtube gallery demo</a>
        </div>',
				'gallery-box'
			),
			'id'         => 'tab_gal',
			'type'       => 'text',

		));

		$vim_gallery = $gallery_box_meta->add_field(array(
			'id'          => 'vim_gallery',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Vimeo Gallery', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="tab_gallery">',
			'after_group'        => '</div>',
			'tab'  => 'vime',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));
		$gallery_box_meta->add_group_field($vim_gallery, array(
			'name'       => '',
			'desc'       => __(
				' 
        <div class="gboxhelp-text"> 
        	<a href="https://wpthemespace.com/product/gallery-box-pro/?add-to-cart=688" target="_blank">
            <img src="' . plugin_dir_url(dirname(__FILE__)) . 'images/gallery-vimeo.png' . '" alt="pro image" />
            </a><br >
            <a href="' . esc_url('https://gbox.wpteamx.com/vimeo-video-gallery/') . '" target="_blank" class="gbox-demo-link">Show vimeo gallery demo</a>
        </div>',
				'gallery-box'
			),
			'id'         => 'tab_gal',
			'type'       => 'text',

		));

		$tab_gallery = $gallery_box_meta->add_field(array(
			'id'          => 'tab_gallery',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Tab Gallery', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="tab_gallery">',
			'after_group'        => '</div>',
			'tab'  => 'tabgallery',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));
		$gallery_box_meta->add_group_field($tab_gallery, array(
			'name'       => '',
			'desc'       => __(
				' 
        <div class="gboxhelp-text"> 
        	<a href="https://wpthemespace.com/product/gallery-box-pro/?add-to-cart=688" target="_blank">
            <img src="' . plugin_dir_url(dirname(__FILE__)) . 'images/gallery-tab.png' . '" alt="pro image" />
            </a>
        </div>',
				'gallery-box'
			),
			'id'         => 'tab_gal',
			'type'       => 'text',

		));
		$help_groupe = $gallery_box_meta->add_field(array(
			'id'          => 'gbox_help',
			'type'        => 'group',
			'repeatable'  => false,
			'options'     => array(
				'group_title'   => __('Upgrade', 'gallery-box'), // since
				'closed'     => false,
			),
			'before_group'       => '<div id="gbox_helptab">',
			'after_group'        => '</div>',
			'tab'  => 'donation',
			'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
		));
		$gallery_box_meta->add_group_field($help_groupe, array(
			'name'       => '',
			'desc'       => __(
				' 
        <div class="gboxhelp-text"> 
        	<a href="https://wpthemespace.com/product/gallery-box-pro/?add-to-cart=688" target="_blank">
            <img src="' . plugin_dir_url(dirname(__FILE__)) . 'images/gbox-pro.png' . '" alt="pro image" />
            </a>
        </div>',
				'gallery-box'
			),
			'id'         => 'donar_helper',
			'type'       => 'text',

		));


		$gallery_box_info = new_cmb2_box(array(
			'id'            => 'gbox_doc_inf',
			'title'         => __('Video tutorial and demo', 'gallery-box'),
			'object_types'  => array('gallery_box',), // Post type
			'context'       => 'side',
			'priority'      => 'high',
			'show_names'    => true,
		));

		// Regular text field
		$gallery_box_info->add_field(array(
			'name'       => '<a class="button button-primary button-large" target="_blank" href="https://gbox.wpteamx.com/">' . __('View demo and video tutorial', 'gallery-box') . '</a>',
			/*'desc'       => '<a class="five-star" target="_blank"  href="https://wordpress.org/support/plugin/gallery-box/reviews/?filter=5">'.__('If you love the gallery then Please give five stars rating .  Your five stars will encourage me a lot.','gallery-box').'</a>',*/
			'id'         => 'gbox_info',
			'type'       => 'text',
		));
	}
	add_action('cmb2_admin_init', 'gbox_meta_group');
endif;

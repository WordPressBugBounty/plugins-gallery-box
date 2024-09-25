<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'cmb2_tabs_' with your project's prefix.
 *
 * @category WordPress_Plugin
 * @package  Demo_CMB2_Tabs
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     https://github.com/stackadroit/cmb2-extensions
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */


/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object.
 *
 * @return bool             True if metabox should show
 */
function cmb2_tabs_show_if_front_page($cmb) {
    // Don't show this metabox if it's not the front page template.
    if (get_option('page_on_front') !== $cmb->object_id) {
        return false;
    }
    return true;
}


add_action('cmb2_admin_init', 'cmb2_tabs_register_demo_metabox');

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function cmb2_tabs_register_demo_metabox() {
    $prefix = 'cmb2_tabs_';

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_tabs_demo = new_cmb2_box(array(
        'id'            => $prefix.'metabox',
        'title'         => esc_html__('Test Metabox', 'cmb2_tabs'),
        'object_types'  => array('page'), // Post type
        'tabs'      => array(
            'contact' => array(
                'label' => __('Contact', 'cmb2_tabs'),
                'show_on_cb' => 'cmb2_tabs_show_if_front_page',
            ),
            'social'  => array(
                'label' => __('Social Media', 'cmb2_tabs'),
                'icon'  => 'dashicons-share', // Dashicon
            ),
            'note'    => array(
                'label' => __('Note', 'cmb2_tabs'),
                'icon'  => 'dashicons-sos', // Custom icon, using image
            ),
        ),
        // 'show_on_cb' => 'cmb2_tabs_show_if_front_page', // function should return a bool value
        // 'context'    => 'normal',
        // 'priority'   => 'high',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
    ));


    
    /*******************GROUPS**************************/
    $group_field_id = $cmb_tabs_demo->add_field( array(
        'id'          => 'wiki_test_repeat_group',
        'type'        => 'group',
        'description' => __( 'Generates reusable form entries', 'cmb2_tabs' ),
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Entry {#}', 'cmb2_tabs' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another Entry', 'cmb2_tabs' ),
            'remove_button' => __( 'Remove Entry', 'cmb2_tabs' ),
            'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $cmb_tabs_demo->add_group_field( $group_field_id, array(
        'name' => __( 'Entry Title', 'cmb2_tabs' ),
        'id'   => 'title',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ) );
        
    /*******************GROUPS**************************/
    $group_field_id = $cmb_tabs_demo->add_field( array(
        'id'          => 'wiki_test_repeat_group',
        'type'        => 'group',
        'description' => __( 'Generates reusable form entries', 'cmb2_tabs' ),
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Entry {#}', 'cmb2_tabs' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another Entry', 'cmb2_tabs' ),
            'remove_button' => __( 'Remove Entry', 'cmb2_tabs' ),
            'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $cmb_tabs_demo->add_group_field( $group_field_id, array(
        'name' => __( 'Entry Title', 'cmb2_tabs' ),
        'id'   => 'title',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ) );   /*******************GROUPS**************************/
    $group_field_id2 = $cmb_tabs_demo->add_field( array(
        'id'          => 'wiki_test_repeat_group1',
        'type'        => 'group',
        'description' => __( 'Generates reusable form entries', 'cmb2_tabs' ),
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Entry {#}', 'cmb2_tabs' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another Entry', 'cmb2_tabs' ),
            'remove_button' => __( 'Remove Entry', 'cmb2_tabs' ),
            'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $cmb_tabs_demo->add_group_field( $group_field_id2, array(
        'name' => __( 'Entry Title', 'cmb2_tabs' ),
        'id'   => 'title',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ) );
    
}


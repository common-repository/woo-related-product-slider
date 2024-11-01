<?php
/**
 * WooCommerce Related Product Slider Settings
 */
if ( !class_exists('WPL_WRPS_Settings' ) ):
class WPL_WRPS_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WPL_WRPS_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu'), 99 );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
	    add_submenu_page( 'woocommerce', __('WooCommerce Related Product Slider Settings', 'woo-related-product-slider'), __('WRPS Settings', 'woo-related-product-slider'), 'manage_options', 'wrps_settings', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'general_settings',
                'title' => __( 'General Settings', 'woo-related-product-slider' )
            ),
            array(
                'id'    => 'stylization',
                'title' => __( 'Stylization', 'woo-related-product-slider' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'general_settings' => array(
                array(
                    'name'              => 'section_title',
                    'label'             => __( 'Section Title', 'woo-related-product-slider' ),
                    'desc'              => __( 'Set related products section title.', 'woo-related-product-slider' ),
                    'type'              => 'text',
                    'default'           => 'Related Products',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'total_products',
                    'label'             => __( 'Total Products', 'woo-related-product-slider' ),
                    'desc'              => __( 'Number of total products to display.', 'woo-related-product-slider' ),
                    'type'              => 'number',
                    'default'           => '50',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'columns',
                    'label'             => __( 'Columns', 'woo-related-product-slider' ),
                    'desc'              => __( 'Column number in slider.', 'woo-related-product-slider' ),
                    'type'              => 'number',
                    'default'           => '3',
                    'sanitize_callback' => 'intval'
                ),
            ),
            'stylization' => array(
                array(
                    'name'    => 'section_title_color',
                    'label'   => __( 'Section Title Color', 'woo-related-product-slider' ),
                    'desc'    => __( 'Set section title color.', 'woo-related-product-slider' ),
                    'type'    => 'color',
                    'default' => '#333333'
                ),
                array(
                    'name'    => 'arrows_color',
                    'label'   => __( 'Arrows Color', 'woo-related-product-slider' ),
                    'desc'    => __( 'Set arrows color.', 'woo-related-product-slider' ),
                    'type'    => 'color',
                    'default' => '#ffffff'
                ),
                array(
                    'name'    => 'arrows_bg',
                    'label'   => __( 'Arrows Background', 'woo-related-product-slider' ),
                    'desc'    => __( 'Set arrows background color.', 'woo-related-product-slider' ),
                    'type'    => 'color',
                    'default' => '#333333'
                ),
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';
        $this->settings_api->show_navigation();
	    if ( isset( $_GET['settings-updated'] ) ) { ?>
            <div id="message" class="updated inline">
                <p><strong><?php _e( 'Your settings have been saved.', 'woo-related-product-slider' ); ?></strong></p>
            </div>
	    <?php }
        $this->settings_api->show_forms();
        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

new WPL_WRPS_Settings();

/**
 * Get the value of a settings field
 * @param $option
 * @param $section
 * @param string $default
 *
 * @return string
 */
if( !function_exists('wpl_wrps_get_option') ){
	function wpl_wrps_get_option( $option, $section, $default = '' ) {
		$options = get_option( $section );
		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}
		return $default;
	}
}
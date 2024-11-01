<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Scripts and styles
 */
class WPL_WRPS_Scripts{

	/**
	 * Script version number
	 */
	protected $version;

	/**
	 * Initialize the class
	 */
	public function __construct() {
		$this->version = '20170828';

		add_action( 'wp_enqueue_scripts', array( $this, 'wpl_wrps_front_scripts' ) );
	}

	/**
	 * Front Scripts
	 */
	public function wpl_wrps_front_scripts() {
		// CSS Files
		wp_enqueue_style( 'slick', WPL_WOO_RELATED_PRODUCT_SLIDER_URL . 'assets/css/slick.css', false, $this->version );
		wp_enqueue_style( 'font-awesome', WPL_WOO_RELATED_PRODUCT_SLIDER_URL . 'assets/css/font-awesome.min.css', false, $this->version );
		wp_enqueue_style( 'wrps-style', WPL_WOO_RELATED_PRODUCT_SLIDER_URL . 'assets/css/style.css', false, $this->version );

		//JS Files
		wp_enqueue_script( 'slick-min-js', WPL_WOO_RELATED_PRODUCT_SLIDER_URL . 'assets/js/slick.min.js', array( 'jquery' ), $this->version, true );
	}

}
new WPL_WRPS_Scripts();
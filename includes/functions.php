<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


/**
 * Functions
 */
class WPL_Woo_Related_Product_Slider_Functions {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'wpl_wrps_remove_hooks' ) );
		add_action( 'plugins_loaded', array( $this, 'wpl_wrps_load_text_domain' ) );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'wpl_woo_related_product_slider' ), 22);
	}

	/**
	 * Load plugin TextDomain
	 */
	function wpl_wrps_load_text_domain() {
		load_plugin_textdomain( 'woo-related-product-slider', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Remove Hooks
	 */
	function wpl_wrps_remove_hooks() {
		//Remove WooCommerce default related products.
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

	/**
	 * Add Related Product Slider after single product summary.
	 */
	function wpl_woo_related_product_slider() {

		global $product;

		if ( empty( $product ) || ! $product->exists() ) {
			return;
		}

		$total_products = wpl_wrps_get_option( 'total_products', 'general_settings', '50' );
		$related_products = wc_get_related_products( $product->get_id(), $total_products, $product->get_upsell_ids() );

		if ( $related_products ) {

			$args = array(
				'post__not_in'        => array( get_the_ID() ),
				'post__in'            => $related_products,
				'posts_per_page'      => -1,
				'post_type'           => 'product',
				'orderby'             => 'rand',
				'order'               => 'DESC',
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => 1,
			);

			$wp_query = new WP_Query( $args );
			$slider_id = uniqid();
			$columns = wpl_wrps_get_option( 'columns', 'general_settings', '3' );
			$section_title = wpl_wrps_get_option( 'section_title', 'general_settings', 'Related Products' );
			$section_title_color = wpl_wrps_get_option( 'section_title_color', 'stylization', '#333333' );
			$arrows_color = wpl_wrps_get_option( 'arrows_color', 'stylization', '#ffffff' );
			$arrows_bg = wpl_wrps_get_option( 'arrows_bg', 'stylization', '#333333' );

			$outline = '';

			$outline .= '<style type="text/css">
			.wpl-woo-related-product-slider-section #wpl-woo-related-product-slider-' . $slider_id . ' .slick-prev, 
			.wpl-woo-related-product-slider-section #wpl-woo-related-product-slider-' . $slider_id . ' .slick-next{
				color: '.$arrows_color.';
				background-color: '.$arrows_bg.';
			}
			</style>';
			$outline .= '
            <script type="text/javascript">
                    jQuery(document).ready(function() {
                    jQuery("#wpl-woo-related-product-slider-' . $slider_id . '").slick({
                        infinite: true,
                        pauseOnHover: true,
                        slidesToShow: '.$columns.',
                        arrows: true,
                        prevArrow: "<div class=\'slick-prev\'><i class=\'fa fa-angle-left\'></i></div>",
                        nextArrow: "<div class=\'slick-next\'><i class=\'fa fa-angle-right\'></i></div>",
                        dots: false,
                        speed: 300,
                 		slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 3000,
                        responsive: [
                                {
                                  breakpoint: 1100,
                                  settings: {
                                    slidesToShow: 3
                                  }
                                },
                                {
                                  breakpoint: 991,
                                  settings: {
                                    slidesToShow: 2
                                  }
                                },
                                {
                                  breakpoint: 667,
                                  settings: {
                                    slidesToShow: 1
                                  }
                                }
                              ]
        
                    });
        
                });
            </script>';

			if ( $wp_query->have_posts() ):

				$outline .= '<div class="wpl-woo-related-product-slider-section">';
				$outline .= '<h2 class="wpl-wrps-section-title" style="color: '.$section_title_color.';">'. $section_title .'</h2>';
				$outline .= '<div class="wpl-wrps-products" id="wpl-woo-related-product-slider-' . $slider_id . '">';
				while ( $wp_query->have_posts() ) : $wp_query->the_post();
					global $product;
					$price_html = $product->get_price_html();
					$outline .= '<div class="wpl-wrps-item '. join(' ', get_post_class()).'">';
					if ( $product->is_on_sale() ) {
						$outline .= apply_filters( 'woocommerce_sale_flash', '<span class="wpl-wrps-on-sale">' . __( 'Sale!', 'woo-related-product-slider'	) . '</span>', $product );
					}

					$outline .= '<a href="'.get_the_permalink().'" class="wpl-wrps-thumbnail">' .woocommerce_get_product_thumbnail() .'</a>';
					$outline .= '<h3 class="wpl-wrps-title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
					$outline .= '<div class="wpl-wrps-price">'.$price_html.'</div>';
					$outline .= '<div class="wpl-wrps-add-to-card">' . do_shortcode( '[add_to_cart id="' . $wp_query->post->ID . '"]' ) . '</div>';

					$outline .= '</div>';//wpl-wrps-item
				endwhile;
				$outline .= '</div>';//wpl-wrps-products
				$outline .= '</div>';//wpl-woo-related-product-slider-section
			endif;
			wp_reset_postdata();

			echo $outline;
		}
	}


}

new WPL_Woo_Related_Product_Slider_Functions();

/**
 * Include files
 */
require_once( WPL_WOO_RELATED_PRODUCT_SLIDER_DIR . 'includes/admin/class-settings.php' );
require_once( WPL_WOO_RELATED_PRODUCT_SLIDER_DIR . 'includes/admin/settings.php' );
require_once( WPL_WOO_RELATED_PRODUCT_SLIDER_DIR . 'includes/scripts.php' );
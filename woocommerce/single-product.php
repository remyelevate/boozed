<?php
/**
 * Template for single WooCommerce product (PDP).
 * Uses design system PDP view. Lives in woocommerce/ folder for proper template inclusion.
 *
 * @see https://woocommerce.com/document/template-structure/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();

	if ( get_post_type() !== 'product' ) {
		continue;
	}

	global $product;
	if ( ! $product ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		continue;
	}

	$pdp_view = get_template_directory() . '/resources/views/single-product/pdp.php';
	if ( is_file( $pdp_view ) ) {
		include $pdp_view;
	}
}

get_footer();

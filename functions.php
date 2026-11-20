<?php
/**
 * Monsieur Dioss — fonctions du thème.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MD_VER', '1.0.0' );
define( 'MD_DIR', get_template_directory() );
define( 'MD_URI', get_template_directory_uri() );

/* ---------- Réglages modifiables (Apparence > Personnaliser > Monsieur Dioss) ---------- */
function md_opt( $key ) {
	$defaults = array(
		'whatsapp'        => '221772526652',
		'phone_display'   => '77 252 66 52',
		'email'           => 'contact@monsieurdioss.com',
		'zone'            => 'Dakar et banlieue',
		'chariow_manuel'  => 'https://slmhubkg.mychariow.com',
		'chariow_academy' => 'https://slmhubkg.mychariow.com',
		'academy_date'    => '[DATE]',
		'facebook'        => '',
		'instagram'       => '',
		'tiktok'          => '',
	);
	$v = get_theme_mod( 'md_' . $key, '' );
	return ( '' === $v || null === $v ) ? ( isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' ) : $v;
}

function md_wa( $message = '' ) {
	$url = 'https://wa.me/' . preg_replace( '/\D/', '', md_opt( 'whatsapp' ) );
	if ( $message ) { $url .= '?text=' . rawurlencode( $message ); }
	return $url;
}

function md_img( $file ) { return esc_url( MD_URI . '/assets/img/' . $file ); }

function md_page_url( $slug ) {
	$p = get_page_by_path( $slug );
	return $p ? get_permalink( $p ) : home_url( '/' . $slug . '/' );
}

function md_shop_url() {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique/' );
}

function md_blog_url() {
	$id = (int) get_option( 'page_for_posts' );
	return $id ? get_permalink( $id ) : md_page_url( 'blog' );
}

function md_icon( $id, $class = 'ico' ) {
	return '<svg class="' . esc_attr( $class ) . '" aria-hidden="true"><use href="#' . esc_attr( $id ) . '"/></svg>';
}

/* ---------- Support du thème ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'custom-logo' );
	register_nav_menus( array( 'primary' => 'Menu principal' ) );
	add_image_size( 'md-card', 800, 450, true );
} );

/* ---------- Styles et scripts ---------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'md-fonts', 'https://fonts.googleapis.com/css2?family=Archivo:wght@600;800;900&family=Inter:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'md-main', MD_URI . '/assets/css/main.css', array(), MD_VER );
	if ( is_page( 'formation' ) ) {
		wp_enqueue_style( 'md-formation', MD_URI . '/assets/css/formation.css', array( 'md-main' ), MD_VER );
	}
	wp_enqueue_script( 'md-main', MD_URI . '/assets/js/main.js', array(), MD_VER, true );
} );

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

/* ---------- Personnaliser ---------- */
add_action( 'customize_register', function ( $wp ) {
	$wp->add_section( 'md_section', array( 'title' => 'Monsieur Dioss', 'priority' => 30 ) );
	$fields = array(
		'whatsapp'        => 'Numéro WhatsApp (format international, ex. 221772526652)',
		'phone_display'   => 'Téléphone affiché',
		'email'           => 'E-mail de contact',
		'zone'            => 'Zone de livraison et d\'intervention',
		'chariow_manuel'  => 'Lien Chariow — Manuel Professionnel (99 000 FCFA)',
		'chariow_academy' => 'Lien Chariow — Business Academy (149 000 FCFA)',
		'academy_date'    => 'Date d\'accès aux vidéos de l\'Academy',
		'facebook'        => 'Lien page Facebook',
		'instagram'       => 'Lien Instagram',
		'tiktok'          => 'Lien TikTok',
	);
	foreach ( $fields as $k => $label ) {
		$wp->add_setting( 'md_' . $k, array( 'default' => '', 'sanitize_callback' => ( false !== strpos( $k, 'chariow' ) || in_array( $k, array( 'facebook', 'instagram', 'tiktok' ), true ) ) ? 'esc_url_raw' : 'sanitize_text_field' ) );
		$wp->add_control( 'md_' . $k, array( 'label' => $label, 'section' => 'md_section', 'type' => 'text' ) );
	}
} );

/* ---------- WooCommerce ---------- */
add_filter( 'woocommerce_enqueue_styles', function ( $styles ) {
	unset( $styles['woocommerce-layout'] );
	return $styles;
} );
add_filter( 'loop_shop_columns', function () { return 4; } );
add_filter( 'loop_shop_per_page', function () { return 16; } );
add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
add_filter( 'wc_get_price_thousand_separator', function () { return ' '; } );
add_filter( 'woocommerce_currency_symbol', function ( $symbol, $currency ) {
	return 'XOF' === $currency ? 'FCFA' : $symbol;
}, 10, 2 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

function md_wa_product_message( $product ) {
	$price = html_entity_decode( wp_strip_all_tags( wc_price( $product->get_price() ) ), ENT_QUOTES, 'UTF-8' );
	$price = str_replace( "\xC2\xA0", ' ', $price );
	return sprintf( 'Bonjour Monsieur Dioss, je veux commander : %s (%s). Merci de me confirmer la livraison.', html_entity_decode( wp_strip_all_tags( $product->get_name() ), ENT_QUOTES, 'UTF-8' ), $price );
}

add_action( 'woocommerce_after_add_to_cart_form', function () {
	global $product;
	if ( ! $product ) { return; }
	echo '<a class="md-wa-btn" href="' . esc_url( md_wa( md_wa_product_message( $product ) ) ) . '" target="_blank" rel="noopener">' . md_icon( 'wa' ) . 'Commander sur WhatsApp</a>';
	echo '<div class="md-trust"><div>Fabriqué au Sénégal</div><div>Livraison ' . esc_html( md_opt( 'zone' ) ) . '</div><div>Wave · Orange Money</div></div>';
} );

add_action( 'woocommerce_after_shop_loop_item', function () {
	global $product;
	if ( ! $product ) { return; }
	echo '<a class="md-wa-loop" href="' . esc_url( md_wa( md_wa_product_message( $product ) ) ) . '" target="_blank" rel="noopener">' . md_icon( 'wa' ) . 'WhatsApp</a>';
}, 20 );

add_filter( 'woocommerce_add_to_cart_fragments', function ( $f ) {
	ob_start();
	echo '<b class="md-cart-count">' . esc_html( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ) . '</b>';
	$f['b.md-cart-count'] = ob_get_clean();
	return $f;
} );

/* ---------- Blog ---------- */
add_filter( 'excerpt_length', function () { return 24; } );
add_filter( 'excerpt_more', function () { return '…'; } );

function md_reading_time( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) round( $words / 200 ) );
}

/** Bloc d'appel à l'action en fin d'article, choisi selon la catégorie. */
function md_post_cta() {
	$cats = wp_list_pluck( get_the_category(), 'slug' );
	if ( array_intersect( $cats, array( 'fabriquer-entreprendre' ) ) ) {
		return array( 'Devenez fabricant avec Monsieur Dioss', 'Formulations professionnelles, contrôle qualité, coûts et plan commercial : la méthode d\'une marque qui fabrique et vend déjà.', md_page_url( 'formation' ), 'Découvrir la formation', false );
	}
	if ( array_intersect( $cats, array( 'entreprises-bureaux' ) ) ) {
		return array( 'Confiez l\'entretien de vos locaux à nos équipes', 'Abonnement sur mesure, superviseur dédié, produits Monsieur Dioss.', md_wa( 'Bonjour, je souhaite un devis pour l\'entretien de nos locaux.' ), 'Demander un devis sur WhatsApp', true );
	}
	return array( 'Les produits Monsieur Dioss, livrés chez vous', 'Nettoyants carreaux & WC, déboucheur NDOMBO : fabriqués au Sénégal, livrés à ' . md_opt( 'zone' ) . '.', md_shop_url(), 'Voir la boutique', false );
}

/* ---------- Nettoyage de l'en-tête ---------- */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_filter( 'body_class', function ( $c ) { $c[] = 'md'; return $c; } );

require MD_DIR . '/inc/parts.php';
require MD_DIR . '/inc/setup.php';

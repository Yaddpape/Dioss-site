<?php
/**
 * Monsieur Dioss — fonctions du thème.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MD_VER', '1.6.1' );
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
		'academy_date'    => '',
		'facebook'        => '',
		'instagram'       => '',
		'tiktok'          => '',
		'legal_name'      => '',
		'ninea'           => '',
		'rccm'            => '',
		'address'         => '',
		'publisher'       => '',
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
		'legal_name'      => 'Pages légales : raison sociale (laisser vide si aucune)',
		'ninea'           => 'Pages légales : NINEA',
		'rccm'            => 'Pages légales : RCCM',
		'address'         => 'Pages légales : adresse (par défaut « Dakar, Sénégal »)',
		'publisher'       => 'Pages légales : responsable de la publication',
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

/* Prix au format sénégalais : « 2 500 FCFA » */
add_filter( 'pre_option_woocommerce_currency_pos', function ( $pos ) {
	return 'XOF' === get_option( 'woocommerce_currency' ) ? 'right_space' : $pos;
} );
add_filter( 'woocommerce_show_page_title', '__return_false' );
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
add_filter( 'woocommerce_sale_flash', function () { return '<span class="onsale">Promo</span>'; } );
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
	return ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) ? 'Ajouter' : $text;
}, 10, 2 );

/** En-tête de la boutique et des catégories de produits. */
function md_shop_hero() {
	$term  = is_product_taxonomy() ? get_queried_object() : null;
	$title = $term ? $term->name : 'La boutique Monsieur Dioss';
	$lead  = ( $term && $term->description ) ? wp_strip_all_tags( $term->description ) : 'Produits d\'entretien formulés et fabriqués au Sénégal, testés chaque jour sur nos chantiers.';
	echo '<section class="page-hero shop-hero"><div class="bg"><img src="' . md_img( 'gamme.jpg' ) . '" alt=""><div class="glass" aria-hidden="true"><i class="fog"></i><i class="gleam"></i><i class="blade"></i></div></div><div class="wrap">';
	echo '<div class="crumbs"><a href="' . esc_url( home_url( '/' ) ) . '">Accueil</a> / ' . ( $term ? '<a href="' . esc_url( md_shop_url() ) . '">Boutique</a> / ' . esc_html( $term->name ) : 'Boutique' ) . '</div>';
	echo '<h1>' . esc_html( $title ) . '</h1><p class="lead">' . esc_html( $lead ) . '</p>';
	echo '<div class="pills"><span class="pill">Fabriqué au Sénégal</span><span class="pill">Livraison ' . esc_html( md_opt( 'zone' ) ) . '</span><span class="pill">Wave · Orange Money</span><a class="pill pill-a" href="' . esc_url( md_wa( 'Bonjour, je souhaite devenir revendeur Monsieur Dioss.' ) ) . '" target="_blank" rel="noopener">Prix revendeurs par carton ' . md_icon( 'arr' ) . '</a></div>';
	$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) );
	$cats = is_array( $cats ) ? array_filter( $cats, function ( $c ) { return ! in_array( $c->slug, array( 'uncategorized', 'non-classe' ), true ); } ) : array();
	if ( $cats ) {
		echo '<nav class="shop-cats" aria-label="Catégories de produits"><a href="' . esc_url( md_shop_url() ) . '"' . ( $term ? '' : ' class="on" aria-current="page"' ) . '>Tous les produits</a>';
		foreach ( $cats as $c ) {
			$on = $term && (int) $term->term_id === (int) $c->term_id;
			echo '<a href="' . esc_url( get_term_link( $c ) ) . '"' . ( $on ? ' class="on" aria-current="page"' : '' ) . '>' . esc_html( $c->name ) . '</a>';
		}
		echo '</nav>';
	}
	echo '</div></section>';
}

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

/* ---------- Référencement : description et fiche entreprise (données réelles uniquement) ---------- */
function md_default_description() {
	return 'Monsieur Dioss, le patron de la propreté : produits d\'entretien fabriqués au Sénégal, services de nettoyage à ' . md_opt( 'zone' ) . ' et formation pour devenir fabricant.';
}
add_filter( 'aioseo_description', function ( $d ) {
	return ( '' === trim( (string) $d ) && is_front_page() ) ? md_default_description() : $d;
} );
add_action( 'wp_head', function () {
	if ( ! is_front_page() ) { return; }
	if ( ! defined( 'AIOSEO_VERSION' ) ) {
		echo '<meta name="description" content="' . esc_attr( md_default_description() ) . '">' . "\n";
	}
	$same = array_values( array_filter( array( md_opt( 'facebook' ), md_opt( 'instagram' ), md_opt( 'tiktok' ) ) ) );
	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'LocalBusiness',
		'@id'         => home_url( '/#entreprise' ),
		'name'        => 'Monsieur Dioss',
		'slogan'      => 'Le patron de la propreté',
		'description' => md_default_description(),
		'url'         => home_url( '/' ),
		'logo'        => MD_URI . '/assets/img/logo-mascotte.jpg',
		'image'       => MD_URI . '/assets/img/gamme.jpg',
		'telephone'   => '+' . preg_replace( '/\D/', '', md_opt( 'whatsapp' ) ),
		'email'       => md_opt( 'email' ),
		'address'     => array( '@type' => 'PostalAddress', 'addressLocality' => 'Dakar', 'addressCountry' => 'SN' ),
		'areaServed'  => md_opt( 'zone' ),
		'currenciesAccepted' => 'XOF',
		'paymentAccepted'    => 'Wave, Orange Money',
	);
	if ( $same ) { $data['sameAs'] = $same; }
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 5 );

/* ---------- Nettoyage de l'en-tête ---------- */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_filter( 'body_class', function ( $c ) { $c[] = 'md'; return $c; } );

require MD_DIR . '/inc/parts.php';
require MD_DIR . '/inc/setup.php';
require MD_DIR . '/inc/realisations.php';
require MD_DIR . '/inc/catalog.php';
require MD_DIR . '/inc/legal.php';


/* Anciennes pages du thème précédent : redirection 301 vers les nouvelles */
add_action( 'template_redirect', function () {
	if ( ! is_page() ) { return; }
	$map = array( 'services' => 'nettoyage', 'about-us' => 'a-propos', 'contact-us' => 'contact' );
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	if ( isset( $map[ $slug ] ) ) {
		wp_safe_redirect( md_page_url( $map[ $slug ] ), 301 );
		exit;
	}
} );

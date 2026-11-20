<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#07080B">
<link rel="icon" href="<?php echo md_img( 'logo-mascotte.jpg' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#contenu">Aller au contenu</a>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<symbol id="ok" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></symbol>
<symbol id="no" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></symbol>
<symbol id="arr" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></symbol>
<symbol id="cart" viewBox="0 0 24 24"><path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.5L21 8H6.2M10 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
<symbol id="wa" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3z"/></symbol>
<symbol id="play" viewBox="0 0 24 24"><path fill="currentColor" d="M8 5.5v13a1 1 0 0 0 1.5.86l10.4-6.5a1 1 0 0 0 0-1.72L9.5 4.64A1 1 0 0 0 8 5.5z"/></symbol>
<symbol id="menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></symbol>
</svg>
<?php
$md_links = array(
	array( 'Accueil', home_url( '/' ), is_front_page() ),
	array( 'Nettoyage', md_page_url( 'nettoyage' ), is_page( 'nettoyage' ) ),
	array( 'Boutique', md_shop_url(), function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ),
	array( 'Formation', md_page_url( 'formation' ), is_page( 'formation' ) ),
	array( 'Blog', md_blog_url(), is_home() || is_singular( 'post' ) || is_category() ),
	array( 'À propos', md_page_url( 'a-propos' ), is_page( 'a-propos' ) ),
	array( 'Contact', md_page_url( 'contact' ), is_page( 'contact' ) ),
);
$md_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$md_cart  = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : md_shop_url();
?>
<div class="top"><div class="wrap"><span>Livraison à <?php echo esc_html( md_opt( 'zone' ) ); ?> · Fabriqué au Sénégal</span><a href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', md_opt( 'whatsapp' ) ) ); ?>">Commandes : <?php echo esc_html( md_opt( 'phone_display' ) ); ?></a></div></div>
<header class="nav">
<div class="wrap">
<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Monsieur Dioss — accueil"><img src="<?php echo md_img( 'logo-mascotte.jpg' ); ?>" alt="" width="44" height="44"><span><span class="logo-t">MONSIEUR DIOSS</span><br><span class="logo-s">LE PATRON DE LA PROPRETÉ</span></span></a>
<nav class="menu" aria-label="Menu principal">
<?php foreach ( $md_links as $l ) : ?><a<?php echo $l[2] ? ' class="on" aria-current="page"' : ''; ?> href="<?php echo esc_url( $l[1] ); ?>"><?php echo esc_html( $l[0] ); ?></a><?php endforeach; ?>
</nav>
<div class="nav-r">
<a class="cart" href="<?php echo esc_url( $md_cart ); ?>" aria-label="Panier"><?php echo md_icon( 'cart' ); ?><b class="md-cart-count"><?php echo (int) $md_count; ?></b></a>
<a class="btn btn-gold" href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite un devis pour une prestation de nettoyage.' ) ); ?>" target="_blank" rel="noopener">Demander un devis</a>
<button class="burger" id="burger" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="drawer"><?php echo md_icon( 'menu' ); ?></button>
</div>
</div>
<div class="drawer" id="drawer">
<?php foreach ( $md_links as $l ) : ?><a href="<?php echo esc_url( $l[1] ); ?>"><?php echo esc_html( $l[0] ); ?></a><?php endforeach; ?>
</div>
</header>
<main id="contenu">

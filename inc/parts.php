<?php
/** Blocs réutilisables. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Grille de produits WooCommerce (vrais produits de la boutique). */
function md_products_grid( $limit = 4 ) {
	if ( ! function_exists( 'wc_get_products' ) ) { return; }
	$products = wc_get_products( array( 'status' => 'publish', 'limit' => $limit, 'orderby' => 'date', 'order' => 'DESC' ) );
	if ( ! $products ) { return; }
	echo '<div class="prods">';
	$i = 0;
	foreach ( $products as $p ) {
		$img = $p->get_image_id() ? wp_get_attachment_image( $p->get_image_id(), 'woocommerce_thumbnail', false, array( 'alt' => esc_attr( $p->get_name() ), 'loading' => 'lazy' ) ) : '';
		$d   = $i % 4 ? ' d' . ( $i % 4 ) : '';
		echo '<div class="prod rv' . esc_attr( $d ) . '">';
		echo '<a class="im" href="' . esc_url( $p->get_permalink() ) . '">' . ( $p->is_on_sale() ? '<span class="tag">PROMO</span>' : '' ) . $img . '</a>';
		echo '<div class="b"><h3><a style="color:inherit;text-decoration:none" href="' . esc_url( $p->get_permalink() ) . '">' . esc_html( wp_strip_all_tags( $p->get_name() ) ) . '</a></h3>';
		echo '<div class="ds">' . esc_html( wp_trim_words( wp_strip_all_tags( $p->get_short_description() ), 16 ) ) . '</div>';
		echo '<div class="pr">' . wp_kses_post( $p->get_price_html() ) . '</div>';
		echo '<div class="acts">';
		echo '<a class="btn btn-dark add_to_cart_button ajax_add_to_cart" data-product_id="' . esc_attr( $p->get_id() ) . '" data-quantity="1" href="' . esc_url( $p->add_to_cart_url() ) . '" rel="nofollow">' . md_icon( 'cart' ) . 'Panier</a>';
		echo '<a class="btn btn-wa" href="' . esc_url( md_wa( md_wa_product_message( $p ) ) ) . '" target="_blank" rel="noopener">' . md_icon( 'wa' ) . 'WhatsApp</a>';
		echo '</div></div></div>';
		$i++;
	}
	echo '</div>';
}

/** Carte d'article. */
function md_post_card( $i = 0 ) {
	$cat = get_the_category();
	$d   = $i % 3 ? ' d' . ( $i % 3 ) : '';
	echo '<a class="post rv' . esc_attr( $d ) . '" href="' . esc_url( get_permalink() ) . '">';
	echo '<div class="im">' . ( has_post_thumbnail() ? get_the_post_thumbnail( null, 'md-card', array( 'loading' => 'lazy', 'alt' => '' ) ) : '<img src="' . md_img( 'salon.jpg' ) . '" alt="" loading="lazy">' ) . '</div>';
	echo '<div class="b">';
	if ( $cat ) { echo '<div class="cat">' . esc_html( $cat[0]->name ) . '</div>'; }
	echo '<h3>' . esc_html( get_the_title() ) . '</h3><p>' . esc_html( get_the_excerpt() ) . '</p>';
	echo '<span class="more">Lire l\'article ' . md_icon( 'arr' ) . '</span></div></a>';
}

/** Sections « services » (accueil et page Nettoyage). */
function md_services_cards() {
	$items = array(
		array( 'grand-menage', 'salon.jpg', 'Appartement après un grand ménage', 'Grand ménage', 'Nettoyage en profondeur de votre maison ou appartement, en général en une journée.', array( 'Sols, carreaux, sanitaires, cuisine', 'Fin de travaux, déménagement, événement' ) ),
		array( 'abonnement', 'bureaux.jpg', 'Chariot de nettoyage dans un hall de bureaux', 'Abonnement entreprises', 'Passages réguliers pour bureaux, commerces et résidences, selon votre planning.', array( 'Fréquence sur mesure', 'Superviseur dédié sur chaque chantier' ) ),
		array( 'detartrage', 'carreaux.jpg', 'Détartrage de carreaux de salle de bain', 'Détartrage WC & carreaux', 'Notre spécialité d\'origine : des toilettes et carreaux entartrés qui retrouvent leur éclat.', array( 'Produits Monsieur Dioss', 'Photos avant / après' ) ),
	);
	echo '<div class="svc">';
	foreach ( $items as $k => $s ) {
		echo '<div class="card rv' . ( $k ? ' d' . $k : '' ) . '" id="' . esc_attr( $s[0] ) . '"><img src="' . md_img( $s[1] ) . '" alt="' . esc_attr( $s[2] ) . '" loading="lazy"><div class="b"><h3>' . esc_html( $s[3] ) . '</h3><p>' . esc_html( $s[4] ) . '</p><ul>';
		foreach ( $s[5] as $li ) { echo '<li>' . md_icon( 'ok', '' ) . esc_html( $li ) . '</li>'; }
		echo '</ul></div></div>';
	}
	echo '</div>';
}

function md_steps() {
	?>
	<div class="steps" data-draw>
	<div class="step rv"><h3>Votre demande</h3><p>Sur WhatsApp ou par téléphone, avec quelques photos des lieux.</p></div>
	<div class="step rv d1"><h3>Votre devis</h3><p>Un prix clair selon la surface et l'état, avant toute intervention.</p></div>
	<div class="step rv d2"><h3>L'intervention</h3><p>Une équipe encadrée par un superviseur, avec nos propres produits.</p></div>
	<div class="step rv d3"><h3>Le contrôle</h3><p>Photos avant / après et validation avec vous en fin de chantier.</p></div>
	</div>
	<?php
}

function md_contact_cards() {
	?>
	<div class="ct">
	<a class="ctc rv" href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', md_opt( 'whatsapp' ) ) ); ?>"><small>COMMANDES &amp; DEVIS</small><b><?php echo esc_html( md_opt( 'phone_display' ) ); ?></b><span>WhatsApp et appels <?php echo md_icon( 'arr' ); ?></span></a>
	<a class="ctc rv d1" href="mailto:<?php echo esc_attr( md_opt( 'email' ) ); ?>"><small>E-MAIL</small><b style="word-break:break-word"><?php echo esc_html( md_opt( 'email' ) ); ?></b><span>Réponse sous 24 h <?php echo md_icon( 'arr' ); ?></span></a>
	<div class="ctc rv d2"><small>ZONE</small><b><?php echo esc_html( md_opt( 'zone' ) ); ?></b><span>Livraison et interventions</span></div>
	</div>
	<?php
}

function md_page_hero( $eyebrow, $title, $lead = '', $img = 'salon.jpg' ) {
	echo '<section class="page-hero"><div class="bg"><img src="' . md_img( $img ) . '" alt=""><div class="glass" aria-hidden="true"><i class="fog"></i><i class="gleam"></i><i class="blade"></i></div></div><div class="wrap">';
	echo '<div class="crumbs"><a href="' . esc_url( home_url( '/' ) ) . '">Accueil</a> / ' . esc_html( $eyebrow ) . '</div>';
	echo '<h1>' . esc_html( $title ) . '</h1>';
	if ( $lead ) { echo '<p class="lead">' . esc_html( $lead ) . '</p>'; }
	echo '</div></section>';
}

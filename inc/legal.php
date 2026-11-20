<?php
/**
 * Pages légales : mentions légales, CGV, politique de confidentialité.
 * Le texte vit dans le thème ; les informations d'identité viennent de Personnaliser > Monsieur Dioss
 * (une ligne vide n'est jamais affichée). Pages créées une seule fois, retrouvées par slug.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MD_LEGAL_VERSION', '1' );
define( 'MD_LEGAL_UPDATED', '20 novembre 2026' );

function md_legal_pages() {
	return array(
		'mentions-legales'           => 'Mentions légales',
		'conditions-generales-de-vente' => 'Conditions générales de vente',
		'politique-de-confidentialite'  => 'Politique de confidentialité',
	);
}

/* Création unique des pages et branchement sur WooCommerce et WordPress. */
add_action( 'init', function () {
	if ( MD_LEGAL_VERSION === get_option( 'md_legal_done' ) ) { return; }
	$ids = array();
	foreach ( md_legal_pages() as $slug => $title ) {
		$p = get_page_by_path( $slug );
		if ( ! $p ) {
			$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug, 'post_content' => '' ) );
		} else {
			$id = $p->ID;
			if ( 'publish' !== $p->post_status ) { wp_update_post( array( 'ID' => $id, 'post_status' => 'publish' ) ); }
		}
		$ids[ $slug ] = (int) $id;
	}
	if ( ! empty( $ids['conditions-generales-de-vente'] ) && ! get_post_status( (int) get_option( 'woocommerce_terms_page_id' ) ) ) {
		update_option( 'woocommerce_terms_page_id', $ids['conditions-generales-de-vente'] );
	}
	$priv = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( ! empty( $ids['politique-de-confidentialite'] ) && 'publish' !== get_post_status( $priv ) ) {
		update_option( 'wp_page_for_privacy_policy', $ids['politique-de-confidentialite'] );
	}
	update_option( 'md_legal_done', MD_LEGAL_VERSION );
}, 98 );

/** Liens légaux (pied de page). */
function md_legal_links() {
	$out = array();
	foreach ( md_legal_pages() as $slug => $title ) {
		$out[] = '<a href="' . esc_url( md_page_url( $slug ) ) . '">' . esc_html( $title ) . '</a>';
	}
	return implode( '', $out );
}

/** Tableau d'identité : seules les lignes renseignées s'affichent. */
function md_legal_identity() {
	$rows = array(
		'Nom commercial'              => 'Monsieur Dioss',
		'Raison sociale'              => md_opt( 'legal_name' ),
		'NINEA'                       => md_opt( 'ninea' ),
		'RCCM'                        => md_opt( 'rccm' ),
		'Adresse'                     => md_opt( 'address' ) ? md_opt( 'address' ) : 'Dakar, Sénégal',
		'Téléphone / WhatsApp'        => '+' . preg_replace( '/\D/', '', md_opt( 'whatsapp' ) ),
		'E-mail'                      => md_opt( 'email' ),
		'Responsable de la publication' => md_opt( 'publisher' ),
	);
	$html = '<table><tbody>';
	foreach ( $rows as $k => $v ) {
		if ( '' === trim( (string) $v ) ) { continue; }
		$html .= '<tr><th scope="row">' . esc_html( $k ) . '</th><td>' . esc_html( $v ) . '</td></tr>';
	}
	return $html . '</tbody></table>';
}

/** Contenu de chaque page légale. */
function md_legal_content( $slug ) {
	$mail  = esc_html( md_opt( 'email' ) );
	$zone  = esc_html( md_opt( 'zone' ) );
	$wa    = esc_url( md_wa( 'Bonjour Monsieur Dioss, j\'ai une question sur ma commande.' ) );
	$conf  = esc_url( md_page_url( 'politique-de-confidentialite' ) );
	$upd   = '<p class="legal-upd">Dernière mise à jour : ' . esc_html( MD_LEGAL_UPDATED ) . '</p>';

	if ( 'mentions-legales' === $slug ) {
		return $upd
			. '<h2>Éditeur du site</h2><p>Le site monsieurdioss.com est édité par :</p>' . md_legal_identity()
			. '<h2>Hébergement</h2><p>Le site est hébergé par Hostinger International Ltd, 61 Lordou Vironos Street, 6023 Larnaca, Chypre (hostinger.com).</p>'
			. '<h2>Propriété intellectuelle</h2><p>La marque Monsieur Dioss, les logos, la mascotte, les textes, les photos et les vidéos de ce site appartiennent à Monsieur Dioss ou sont utilisés avec autorisation. Toute reproduction, même partielle, sans accord écrit préalable est interdite.</p>'
			. '<h2>Responsabilité</h2><p>Nous veillons à l\'exactitude des informations publiées. Elles peuvent toutefois être modifiées à tout moment, notamment les prix et la disponibilité des produits. Les conseils d\'entretien publiés sur le blog sont donnés à titre informatif : respectez toujours l\'étiquette des produits et les consignes de sécurité.</p>'
			. '<h2>Données personnelles</h2><p>La manière dont nous traitons vos données est décrite dans notre <a href="' . $conf . '">politique de confidentialité</a>.</p>'
			. '<h2>Droit applicable</h2><p>Le présent site et ses mentions légales sont soumis au droit sénégalais.</p>';
	}

	if ( 'conditions-generales-de-vente' === $slug ) {
		return $upd
			. '<p>Les présentes conditions générales de vente (CGV) s\'appliquent aux commandes de produits, aux prestations de nettoyage et aux formations proposées par Monsieur Dioss sur monsieurdioss.com, par WhatsApp ou par téléphone. Passer commande implique leur acceptation.</p>'
			. '<h2>1. Vendeur</h2>' . md_legal_identity()
			. '<h2>2. Produits</h2><p>Les produits sont décrits avec le plus grand soin. Les photos sont fournies à titre d\'illustration ; l\'emballage reçu peut légèrement différer. Les produits sont proposés dans la limite des stocks disponibles.</p>'
			. '<h2>3. Prix</h2><p>Les prix sont indiqués en francs CFA (FCFA). Le prix applicable est celui affiché au moment de la commande. Les éventuels frais de livraison vous sont communiqués avant la validation de la commande.</p>'
			. '<h2>4. Commande</h2><p>Vous pouvez commander sur le site (panier puis validation) ou directement sur WhatsApp. La commande est définitive après sa confirmation par notre équipe, qui peut vous contacter pour vérifier l\'adresse et le créneau de livraison.</p>'
			. '<h2>5. Paiement</h2><p>Le paiement s\'effectue par l\'un des moyens proposés lors de la commande ou confirmés par notre équipe (par exemple Wave ou Orange Money). Les paiements en ligne sont traités par des prestataires de paiement sécurisés ; nous n\'avons jamais accès à vos coordonnées bancaires.</p>'
			. '<h2>6. Livraison</h2><p>Nous livrons à ' . $zone . '. Le délai de livraison vous est indiqué lors de la confirmation de la commande. Vérifiez votre colis à la réception et signalez immédiatement tout produit manquant, abîmé ou qui fuit.</p>'
			. '<h2>7. Réclamations</h2><p>Pour toute réclamation (produit endommagé, erreur de commande, produit non conforme), contactez-nous rapidement <a href="' . $wa . '" target="_blank" rel="noopener">sur WhatsApp</a> ou à ' . $mail . ', avec votre numéro de commande et des photos. Nous étudions chaque demande et vous proposons une solution après vérification.</p>'
			. '<h2>8. Sécurité des produits</h2><p>Nos produits d\'entretien sont des produits chimiques. Lisez l\'étiquette avant usage, portez des gants, travaillez dans un lieu aéré et conservez-les hors de portée des enfants. Ne mélangez jamais deux produits, en particulier l\'eau de Javel avec un produit acide ou un détartrant. Monsieur Dioss ne peut être tenu responsable d\'un usage non conforme aux consignes.</p>'
			. '<h2>9. Prestations de nettoyage</h2><p>Chaque prestation fait l\'objet d\'un devis préalable, établi selon la surface, l\'état des lieux et la fréquence. Le prix applicable est celui du devis accepté. En cas d\'empêchement, prévenez-nous le plus tôt possible pour reporter l\'intervention. Toute remarque sur le résultat doit être faite lors de la validation de fin de chantier.</p>'
			. '<h2>10. Formations</h2><p>Les formations (Manuel Professionnel, Business Academy, Business Elite) sont des contenus et accompagnements numériques. Le paiement est traité par la plateforme Chariow et l\'accès est envoyé par e-mail. L\'accès est personnel : le partage, la copie ou la revente des contenus sont interdits. La formation transmet une méthode et des formulations ; elle ne garantit ni un chiffre d\'affaires ni une rentabilité, qui dépendent de chaque projet. L\'accompagnement Business Elite est accordé sur candidature, selon les places disponibles.</p>'
			. '<h2>11. Données personnelles</h2><p>Les données nécessaires à votre commande sont traitées conformément à notre <a href="' . $conf . '">politique de confidentialité</a>.</p>'
			. '<h2>12. Droit applicable et litiges</h2><p>Les présentes CGV sont soumises au droit sénégalais. En cas de différend, nous rechercherons d\'abord une solution amiable. À défaut, les tribunaux compétents de Dakar seront saisis.</p>';
	}

	if ( 'politique-de-confidentialite' === $slug ) {
		return $upd
			. '<p>Monsieur Dioss attache une grande importance à la protection de vos données personnelles, conformément à la loi sénégalaise n° 2008-12 du 25 janvier 2008 sur la protection des données à caractère personnel.</p>'
			. '<h2>Responsable du traitement</h2>' . md_legal_identity()
			. '<h2>Données que nous collectons</h2><ul><li><strong>Commandes :</strong> nom, téléphone, adresse de livraison, e-mail et contenu de la commande.</li><li><strong>Échanges :</strong> les messages que vous nous envoyez sur WhatsApp, par téléphone ou par e-mail.</li><li><strong>Devis de nettoyage :</strong> les informations et photos des lieux que vous nous transmettez.</li><li><strong>Navigation :</strong> des données de mesure d\'audience et des cookies (voir plus bas).</li></ul>'
			. '<h2>Pourquoi nous les utilisons</h2><ul><li>Traiter, livrer et suivre vos commandes et vos prestations.</li><li>Répondre à vos questions et établir vos devis.</li><li>Améliorer le site et mesurer l\'efficacité de nos publicités.</li><li>Respecter nos obligations légales, notamment comptables.</li></ul>'
			. '<h2>Qui y a accès</h2><p>Vos données sont réservées à l\'équipe Monsieur Dioss et, pour ce qui les concerne, à nos prestataires : livreurs, prestataires de paiement (dont PayPal et Chariow), hébergeur du site (Hostinger) et outils de mesure d\'audience. Nous ne vendons jamais vos données.</p>'
			. '<h2>Cookies et mesure d\'audience</h2><p>Le site utilise des cookies nécessaires à son fonctionnement (panier, commande) ainsi que des outils de mesure d\'audience et de publicité : Google (Site Kit, Google Tag Manager), Microsoft Clarity et le Pixel Meta. Ils nous aident à comprendre l\'utilisation du site et à mesurer nos publicités. Vous pouvez refuser ou supprimer les cookies dans les réglages de votre navigateur.</p>'
			. '<h2>Durée de conservation</h2><p>Les données de commande sont conservées le temps nécessaire à la relation commerciale et aux obligations légales. Les messages et devis sont conservés le temps nécessaire au suivi de votre demande.</p>'
			. '<h2>Vos droits</h2><p>Vous pouvez demander l\'accès à vos données, leur rectification, leur suppression ou vous opposer à leur utilisation en écrivant à ' . $mail . '. Vous pouvez aussi saisir la Commission de protection des données personnelles (CDP) du Sénégal.</p>';
	}
	return '';
}

/** Gabarit commun des pages légales. */
function md_legal_render( $slug ) {
	$pages = md_legal_pages();
	get_header();
	md_page_hero( 'Informations légales', $pages[ $slug ], '', 'bureau-direction.jpg' );
	echo '<section class="sec light"><div class="wrap"><nav class="cats legal-nav" aria-label="Pages légales">';
	foreach ( $pages as $s => $t ) {
		echo '<a href="' . esc_url( md_page_url( $s ) ) . '"' . ( $s === $slug ? ' class="on" aria-current="page"' : '' ) . '>' . esc_html( $t ) . '</a>';
	}
	echo '</nav><div class="prose legal">' . md_legal_content( $slug ) . '</div></div></section>'; // phpcs:ignore -- contenu du thème, valeurs échappées
	get_footer();
}

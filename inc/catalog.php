<?php
/**
 * Mises à jour ponctuelles du catalogue WooCommerce, demandées par Monsieur Dioss.
 * Chaque migration s'exécute une seule fois (option md_catalog_done), vérifie l'état
 * des produits avant de les modifier et ne crée jamais de doublon (recherche par slug).
 * Les photos sont livrées avec le thème dans assets/img/produits/.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MD_CATALOG_VERSION', '2026-11-20b' );

add_action( 'init', function () {
	if ( MD_CATALOG_VERSION === get_option( 'md_catalog_done' ) || ! function_exists( 'wc_get_product' ) ) { return; }
	if ( get_transient( 'md_catalog_lock' ) ) { return; }
	set_transient( 'md_catalog_lock', 1, 5 * MINUTE_IN_SECONDS );
	$log = md_catalog_run();
	update_option( 'md_catalog_log', $log, false );
	if ( empty( $log['errors'] ) ) {
		update_option( 'md_catalog_done', MD_CATALOG_VERSION );
	}
	delete_transient( 'md_catalog_lock' );
}, 99 );

/** Produit par slug (ou null). */
function md_catalog_product( $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'product' );
	return $post ? wc_get_product( $post->ID ) : null;
}

/** Importe une photo du thème dans la médiathèque (une seule fois). */
function md_catalog_image( $file, $alt, $parent = 0 ) {
	$key = 'md_catalog_img_' . md5( $file );
	$id  = (int) get_option( $key );
	if ( $id && get_post( $id ) ) { return $id; }
	$path = MD_DIR . '/assets/img/produits/' . $file;
	if ( ! file_exists( $path ) ) { return 0; }
	$up = wp_upload_bits( $file, null, file_get_contents( $path ) ); // phpcs:ignore -- fichier local du thème
	if ( ! empty( $up['error'] ) ) { return 0; }
	$id = wp_insert_attachment( array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => $alt,
		'post_status'    => 'inherit',
	), $up['file'], $parent );
	if ( ! $id || is_wp_error( $id ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $up['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_option( $key, $id, false );
	return $id;
}

/** Catégorie de produits par slug, créée si besoin. */
function md_catalog_cat( $slug, $name ) {
	$t = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $t ) { return (int) $t->term_id; }
	$r = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
	return is_wp_error( $r ) ? 0 : (int) $r['term_id'];
}

function md_catalog_run() {
	$log = array( 'done' => array(), 'errors' => array() );

	/* 1. Spray nettoyant carreaux : 600 g devient 500 g, prix inchangé, nouvelle photo. */
	$p = md_catalog_product( 'monsieur-dioss-nettoyant-carreaux-multi-surfaces-600-ml' );
	if ( $p ) {
		$fix = function ( $s ) { return str_replace( array( '600 grammes', '600 gr', '600 ml', '600ml', '600 g' ), array( '500 grammes', '500 g', '500 g', '500 g', '500 g' ), $s ); };
		$p->set_name( $fix( $p->get_name() ) );
		$p->set_short_description( $fix( $p->get_short_description() ) );
		$p->set_description( $fix( $p->get_description() ) );
		$img = md_catalog_image( 'nettoyant-carreaux-spray-500g.jpg', 'Monsieur Dioss Nettoyant Carreaux spray 500 g', $p->get_id() );
		if ( $img ) {
			$old     = (int) $p->get_image_id();
			$gallery = array_filter( array_merge( array( $old ), $p->get_gallery_image_ids() ), function ( $id ) use ( $img ) {
				$f = basename( (string) get_attached_file( $id ) );
				return $id && $id !== $img && false === strpos( $f, '600' ) && 0 !== strpos( $f, 'DIOSS-CARREAUX-4' );
			} );
			$p->set_image_id( $img );
			$p->set_gallery_image_ids( array_values( array_unique( $gallery ) ) );
		} else {
			$log['errors'][] = 'image spray';
		}
		$p->save();
		$log['done'][] = 'spray 500 g #' . $p->get_id();
	} else {
		$log['done'][] = 'spray introuvable (déjà renommé ?)';
	}

	/* 2. Nettoyant 1 L : corrige « 600 ml » dans le résumé et la liste des formats. */
	$p = md_catalog_product( 'monsieur-dioss-nettoyant-carreaux-multi-surfaces-1-litre-anti-trace-degraissant-puissant' );
	if ( $p ) {
		$p->set_short_description( str_replace( array( 'Multi-Surfaces 600 ml', '600 ml' ), array( 'Multi-Surfaces 1 Litre', '1 L' ), $p->get_short_description() ) );
		$p->set_description( str_replace( '600 gr', '500 g', $p->get_description() ) );
		$p->save();
		$log['done'][] = '1 L #' . $p->get_id();
	}

	/* 3. NDOMBO : nouvelle photo. */
	$p = md_catalog_product( 'ndombo-deboucheur-anti-odeur-solution-efficace-pour-wc-douche-et-cuisine-monsieur-dioss-senegal' );
	if ( $p ) {
		$img = md_catalog_image( 'ndombo-500g.jpg', 'NDOMBO déboucheur et anti-odeur Monsieur Dioss, pots de 500 g', $p->get_id() );
		if ( $img ) {
			$p->set_image_id( $img );
			$p->save();
			$log['done'][] = 'ndombo photo #' . $p->get_id();
		} else {
			$log['errors'][] = 'image ndombo';
		}
	}

	/* 4. Nouveaux produits. */
	$safety = '<h3>Précautions</h3><ul><li>Portez des gants. Évitez tout contact avec les yeux et la peau.</li><li>Ne mélangez jamais avec un autre produit, en particulier l\'eau de Javel.</li><li>Utilisez dans un lieu aéré. Conservez hors de portée des enfants.</li><li>En cas de contact, rincez abondamment à l\'eau et consultez un médecin.</li></ul>';
	$new = array(
		array(
			'slug'  => 'monsieur-dioss-benet-degraissant-5-litres',
			'name'  => 'Monsieur DIOSS BENET – Dégraissant graisses incrustées 5 Litres',
			'price' => '24000',
			'cat'   => array( 'degraissants', 'Dégraissants' ),
			'img'   => array( 'benet-degraissant-5l.jpg', 'Bidon de 5 litres de BENET, dégraissant Monsieur Dioss' ),
			'short' => '<p><strong>Décolle la graisse collée !</strong> BENET est le décolleur de graisse professionnel de Monsieur DIOSS, en bidon de 5 litres.</p>',
			'desc'  => '<h3>Le décolleur de graisse professionnel</h3><p>BENET s\'attaque aux graisses incrustées de la cuisine.</p><h3>Pour</h3><ul><li>Équipements de cuisine</li><li>Hottes aspirantes</li><li>Plans de travail</li><li>Ustensiles de cuisson</li></ul><h3>Informations</h3><ul><li>Contenance : 5 litres</li><li>Marque : Monsieur DIOSS</li></ul>' . $safety,
		),
		array(
			'slug'  => 'monsieur-dioss-ultra-nettoyant-carreaux-wc-5-litres',
			'name'  => 'Monsieur DIOSS ULTRA – Nettoyant Carreaux et WC 5 Litres',
			'price' => '25000',
			'cat'   => array( 'nettoyants-carreaux-wc', 'Nettoyants carreaux & WC' ),
			'img'   => array( 'ultra-nettoyant-carreaux-wc-5l.jpg', 'Monsieur Dioss ULTRA, nettoyant carreaux et WC, bidon de 5 litres' ),
			'short' => '<p><strong>Monsieur DIOSS ULTRA</strong>, le nettoyant carreaux et WC en format 5 litres.</p>',
			'desc'  => '<h3>Nettoyant carreaux et WC</h3><p>La formule ULTRA de Monsieur DIOSS pour les carreaux et les WC, en grand format.</p><h3>Informations</h3><ul><li>Contenance : 5 litres</li><li>Marque : Monsieur DIOSS</li></ul><p><strong>Produit corrosif : il peut provoquer de graves brûlures.</strong> Lisez attentivement les instructions de l\'étiquette avant utilisation.</p>' . $safety,
		),
	);
	foreach ( $new as $n ) {
		if ( md_catalog_product( $n['slug'] ) ) { $log['done'][] = 'existe déjà : ' . $n['slug']; continue; }
		$p = new WC_Product_Simple();
		$p->set_name( $n['name'] );
		$p->set_slug( $n['slug'] );
		$p->set_status( 'publish' );
		$p->set_catalog_visibility( 'visible' );
		$p->set_regular_price( $n['price'] );
		$p->set_short_description( $n['short'] );
		$p->set_description( $n['desc'] );
		$cat = md_catalog_cat( $n['cat'][0], $n['cat'][1] );
		if ( $cat ) { $p->set_category_ids( array( $cat ) ); }
		$id  = $p->save();
		$img = md_catalog_image( $n['img'][0], $n['img'][1], $id );
		if ( $img ) { $p->set_image_id( $img ); $p->save(); } else { $log['errors'][] = 'image ' . $n['slug']; }
		$log['done'][] = 'créé #' . $id . ' ' . $n['slug'];
	}

	/* 5. Ouverture de la boutique au public (demandée le 20/11/2026) : fin du mode « Bientôt disponible ». */
	if ( 'yes' === get_option( 'woocommerce_coming_soon' ) ) {
		update_option( 'woocommerce_coming_soon', 'no' );
		$log['done'][] = 'boutique ouverte';
	}

	if ( function_exists( 'wc_delete_product_transients' ) ) { wc_delete_product_transients(); }
	return $log;
}

/* Vidéos TikTok envoyées le 21/11/2026 : import en tâche de fond (appels à TikTok), 3 essais au plus. */
define( 'MD_VIDEOS_VERSION', '2026-11-21' );
add_action( 'init', function () {
	if ( MD_VIDEOS_VERSION === get_option( 'md_videos_done' ) || wp_next_scheduled( 'md_import_videos' ) ) { return; }
	wp_schedule_single_event( time(), 'md_import_videos' );
}, 100 );
add_action( 'md_import_videos', function () {
	if ( MD_VIDEOS_VERSION === get_option( 'md_videos_done' ) ) { return; }
	$log   = md_catalog_videos( array( 'done' => array(), 'errors' => array() ) );
	$tries = (int) get_option( 'md_videos_tries' ) + 1;
	update_option( 'md_videos_tries', $tries, false );
	update_option( 'md_videos_log', $log, false );
	if ( empty( $log['errors'] ) || $tries >= 3 ) {
		update_option( 'md_videos_done', MD_VIDEOS_VERSION );
	} else {
		wp_schedule_single_event( time() + 10 * MINUTE_IN_SECONDS, 'md_import_videos' );
	}
} );

/** Importe les vidéos TikTok : fiche « Vidéo chantier », aperçu officiel, profil TikTok. */
function md_catalog_videos( $log ) {
	if ( ! post_type_exists( 'md_video' ) ) { $log['errors'][] = 'type md_video absent'; return $log; }
	$videos = array(
		array( 'https://vm.tiktok.com/ZGdXGgnqP/', 'Démonstration du nettoyant carreaux Monsieur Dioss', 'Démonstration produit' ),
		array( 'https://vm.tiktok.com/ZGdXGPhcY/', 'Démonstration de BENET, le dégraissant, en cuisine', 'Démonstration produit' ),
		array( 'https://vm.tiktok.com/ZGdXGXLAf/', 'Chantier à la Cité Keur Gorgui', 'Cité Keur Gorgui, Dakar' ),
		array( 'https://vm.tiktok.com/ZGdXGt1B6/', 'Nettoyage aux Mamelles', 'Les Mamelles, Dakar' ),
		array( 'https://vm.tiktok.com/ZGdXGbLsJ/', 'Grattage de murs à peinture dure', 'Liberté 6 · Chantier de déménagement' ),
	);
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	foreach ( $videos as $i => $v ) {
		list( $short, $title, $lieu ) = $v;
		$found = get_posts( array( 'post_type' => 'md_video', 'post_status' => 'any', 'numberposts' => 1, 'meta_key' => '_md_video_src', 'meta_value' => $short, 'fields' => 'ids' ) );
		$id    = $found ? (int) $found[0] : 0;
		if ( ! $id ) {
			$id = wp_insert_post( array( 'post_type' => 'md_video', 'post_status' => 'publish', 'post_title' => $title, 'menu_order' => $i + 1 ) );
			if ( ! $id || is_wp_error( $id ) ) { $log['errors'][] = 'vidéo ' . $short; continue; }
			update_post_meta( $id, '_md_video_src', $short );
			update_post_meta( $id, '_md_video_lieu', $lieu );
		}
		$full = md_tiktok_resolve( $short );
		update_post_meta( $id, '_md_video_url', $full );
		if ( $full === $short ) { $log['errors'][] = 'lien non résolu ' . $short; continue; }
		if ( has_post_thumbnail( $id ) ) { $log['done'][] = 'vidéo #' . $id; continue; }
		$oe = wp_remote_get( 'https://www.tiktok.com/oembed?url=' . rawurlencode( $full ), array( 'timeout' => 15 ) );
		$oe = is_wp_error( $oe ) ? null : json_decode( wp_remote_retrieve_body( $oe ), true );
		if ( empty( $oe['thumbnail_url'] ) ) { $log['errors'][] = 'aperçu absent ' . $short; continue; }
		if ( ! empty( $oe['author_url'] ) && '' === md_opt( 'tiktok' ) && preg_match( '~^https://www\.tiktok\.com/@[\w.-]+$~', $oe['author_url'] ) ) {
			set_theme_mod( 'md_tiktok', esc_url_raw( $oe['author_url'] ) );
		}
		$tmp = download_url( $oe['thumbnail_url'], 20 );
		if ( is_wp_error( $tmp ) ) { $log['errors'][] = 'téléchargement aperçu ' . $short; continue; }
		$att = media_handle_sideload( array( 'name' => 'tiktok-' . basename( $full ) . '.jpg', 'tmp_name' => $tmp ), $id, $title );
		if ( is_wp_error( $att ) ) { @unlink( $tmp ); $log['errors'][] = 'aperçu ' . $short; continue; } // phpcs:ignore
		update_post_meta( $att, '_wp_attachment_image_alt', $title );
		set_post_thumbnail( $id, $att );
		$log['done'][] = 'vidéo #' . $id . ' ' . $full;
	}
	return $log;
}

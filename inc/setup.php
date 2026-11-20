<?php
/**
 * Mise en place automatique, une seule fois, à l'activation du thème :
 * pages, page d'accueil, blog, catégories, rangement des produits,
 * mise à la corbeille des articles de démonstration, 8 articles en brouillon.
 * Tout est réversible (rien n'est supprimé définitivement).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'MD_SETUP_VERSION', '1' );

add_action( 'after_switch_theme', 'md_run_setup' );
add_action( 'admin_init', function () {
	if ( get_option( 'md_setup_version' ) !== MD_SETUP_VERSION ) { md_run_setup(); }
} );
add_action( 'init', function () {
	if ( get_option( 'md_setup_version' ) !== MD_SETUP_VERSION ) { md_run_setup(); }
}, 99 );

function md_run_setup() {
	if ( get_option( 'md_setup_version' ) === MD_SETUP_VERSION ) { return; }
	if ( get_transient( 'md_setup_lock' ) ) { return; }
	set_transient( 'md_setup_lock', 1, 120 );

	$log = array();

	/* 1. Catégories du blog */
	$cats = array(
		'entretien-maison'       => array( 'Entretien maison', 'Astuces et méthodes pour une maison propre et saine.' ),
		'astuces-cuisine'        => array( 'Astuces cuisine', 'Dégraisser, déboucher, désodoriser : la cuisine sans corvée.' ),
		'entreprises-bureaux'    => array( 'Entreprises & bureaux', 'Entretien des bureaux, commerces et locaux professionnels.' ),
		'fabriquer-entreprendre' => array( 'Fabriquer & entreprendre', 'Créer et développer son entreprise de produits d\'entretien.' ),
	);
	$cat_ids = array();
	foreach ( $cats as $slug => $c ) {
		$t = get_term_by( 'slug', $slug, 'category' );
		if ( ! $t ) {
			$r = wp_insert_term( $c[0], 'category', array( 'slug' => $slug, 'description' => $c[1] ) );
			$cat_ids[ $slug ] = is_wp_error( $r ) ? 0 : (int) $r['term_id'];
		} else {
			$cat_ids[ $slug ] = (int) $t->term_id;
		}
	}

	/* 2. Pages */
	$pages = array(
		'nettoyage' => 'Services de nettoyage',
		'formation' => 'Formation — Lancez votre entreprise de produits de nettoyage',
		'a-propos'  => 'À propos',
		'contact'   => 'Contact',
		'blog'      => 'Blog',
	);
	$page_ids = array();
	foreach ( $pages as $slug => $title ) {
		$p = get_page_by_path( $slug );
		if ( $p ) {
			$page_ids[ $slug ] = (int) $p->ID;
			if ( 'publish' !== $p->post_status ) { wp_update_post( array( 'ID' => $p->ID, 'post_status' => 'publish' ) ); }
		} else {
			$page_ids[ $slug ] = (int) wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug, 'post_content' => '' ) );
			$log[] = 'page ' . $slug;
		}
	}

	/* 3. Page d'accueil statique + page des articles */
	$front = (int) get_option( 'page_on_front' );
	if ( ! $front || ! get_post( $front ) ) {
		$home = get_page_by_path( 'home' );
		if ( ! $home ) { $home = get_page_by_path( 'accueil' ); }
		$front = $home ? (int) $home->ID : (int) wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Accueil', 'post_name' => 'accueil' ) );
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front );
	if ( ! empty( $page_ids['blog'] ) ) { update_option( 'page_for_posts', $page_ids['blog'] ); }

	/* 4. Articles de démonstration du thème précédent : corbeille (récupérables) */
	foreach ( array( 'blandit-turpis-cursus-habitasse-platea', 'viverra-adipiscing-integer-feugiat', 'mattis-nunc-sed-blandit-libero-volutpat' ) as $slug ) {
		$demo = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $demo && 'trash' !== $demo->post_status ) { wp_trash_post( $demo->ID ); $log[] = 'corbeille ' . $slug; }
	}

	/* 5. Catégories de produits WooCommerce */
	if ( taxonomy_exists( 'product_cat' ) ) {
		$pcats = array(
			'nettoyants-carreaux-wc' => array( 'Nettoyants carreaux & WC', array( 1032, 1040 ) ),
			'deboucheurs'            => array( 'Déboucheurs & anti-odeurs', array( 1041 ) ),
			'degraissants'           => array( 'Dégraissants', array() ),
		);
		foreach ( $pcats as $slug => $pc ) {
			$t = get_term_by( 'slug', $slug, 'product_cat' );
			if ( ! $t ) {
				$r  = wp_insert_term( $pc[0], 'product_cat', array( 'slug' => $slug ) );
				$id = is_wp_error( $r ) ? 0 : (int) $r['term_id'];
			} else {
				$id = (int) $t->term_id;
			}
			if ( ! $id ) { continue; }
			foreach ( $pc[1] as $pid ) {
				if ( 'product' === get_post_type( $pid ) ) { wp_set_object_terms( $pid, array( $id ), 'product_cat', false ); }
			}
		}
	}

	/* 6. Les 8 premiers articles, en BROUILLON (à relire puis publier) */
	$articles = include MD_DIR . '/inc/articles.php';
	$pretty   = false !== strpos( (string) get_option( 'permalink_structure' ), '%postname%' );
	$tokens   = array(
		'{{SHOP}}'      => md_shop_url(),
		'{{DEVIS}}'     => md_wa( 'Bonjour, je souhaite un devis pour une prestation de nettoyage.' ),
		'{{FORMATION}}' => get_permalink( $page_ids['formation'] ),
		'{{NETTOYAGE}}' => get_permalink( $page_ids['nettoyage'] ),
		'{{CONTACT}}'   => get_permalink( $page_ids['contact'] ),
		'{{CARREAUX}}'  => 'product' === get_post_type( 1040 ) ? get_permalink( 1040 ) : md_shop_url(),
		'{{NDOMBO}}'    => 'product' === get_post_type( 1041 ) ? get_permalink( 1041 ) : md_shop_url(),
		'{{BLOG_COUT}}' => $pretty ? home_url( '/calculer-cout-de-revient-produit-entretien/' ) : md_blog_url(),
	);
	foreach ( $articles as $a ) {
		if ( get_page_by_path( $a['slug'], OBJECT, 'post' ) ) { continue; }
		$pid = wp_insert_post( array(
			'post_type'     => 'post',
			'post_status'   => 'draft',
			'post_title'    => $a['title'],
			'post_name'     => $a['slug'],
			'post_excerpt'  => $a['excerpt'],
			'post_content'  => strtr( trim( $a['content'] ), $tokens ),
			'post_category' => ! empty( $cat_ids[ $a['cat'] ] ) ? array( $cat_ids[ $a['cat'] ] ) : array(),
		), true );
		if ( is_wp_error( $pid ) ) { continue; }
		$att = md_theme_image_attachment( $a['img'], $pid );
		if ( $att ) { set_post_thumbnail( $pid, $att ); }
		$log[] = 'brouillon ' . $a['slug'];
	}

	update_option( 'md_setup_log', $log, false );
	update_option( 'md_setup_version', MD_SETUP_VERSION );
	delete_transient( 'md_setup_lock' );
	flush_rewrite_rules( false );
}

/** Copie une image du thème dans la médiathèque (une seule fois par fichier). */
function md_theme_image_attachment( $file, $parent = 0 ) {
	$map = get_option( 'md_media_map', array() );
	if ( ! empty( $map[ $file ] ) && get_post( $map[ $file ] ) ) { return (int) $map[ $file ]; }
	$path = MD_DIR . '/assets/img/' . $file;
	if ( ! file_exists( $path ) ) { return 0; }
	$up = wp_upload_bits( 'monsieur-dioss-' . $file, null, file_get_contents( $path ) );
	if ( ! empty( $up['error'] ) ) { return 0; }
	$att = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => 'Monsieur Dioss — ' . pathinfo( $file, PATHINFO_FILENAME ), 'post_status' => 'inherit' ), $up['file'], $parent );
	if ( ! $att || is_wp_error( $att ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $up['file'] ) );
	$map[ $file ] = $att;
	update_option( 'md_media_map', $map, false );
	return (int) $att;
}

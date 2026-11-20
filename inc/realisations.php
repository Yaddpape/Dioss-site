<?php
/**
 * Vidéos de chantiers réels et témoignages clients.
 * Contenus saisis dans l'administration (menus « Vidéos chantiers » et « Témoignages »).
 * Rien n'est affiché aux visiteurs tant qu'aucun contenu réel n'a été publié.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ---------- Types de contenus ---------- */
add_action( 'init', function () {
	register_post_type( 'md_video', array(
		'labels'          => array(
			'name'          => 'Vidéos chantiers',
			'singular_name' => 'Vidéo chantier',
			'add_new'       => 'Ajouter une vidéo',
			'add_new_item'  => 'Ajouter une vidéo de chantier',
			'edit_item'     => 'Modifier la vidéo',
			'all_items'     => 'Toutes les vidéos',
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_position'   => 21,
		'menu_icon'       => 'dashicons-video-alt3',
		'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
		'capability_type' => 'post',
	) );
	register_post_type( 'md_temoignage', array(
		'labels'          => array(
			'name'          => 'Témoignages',
			'singular_name' => 'Témoignage',
			'add_new'       => 'Ajouter un témoignage',
			'add_new_item'  => 'Ajouter un témoignage client',
			'edit_item'     => 'Modifier le témoignage',
			'all_items'     => 'Tous les témoignages',
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_position'   => 22,
		'menu_icon'       => 'dashicons-format-quote',
		'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
		'capability_type' => 'post',
	) );
} );

add_filter( 'enter_title_here', function ( $t, $post ) {
	if ( 'md_video' === $post->post_type ) { return 'Titre (ex. Détartrage complet d\'une salle de bain)'; }
	if ( 'md_temoignage' === $post->post_type ) { return 'Nom du client (ex. Awa D.)'; }
	return $t;
}, 10, 2 );

/* ---------- Champs ---------- */
function md_proof_fields( $type ) {
	if ( 'md_video' === $type ) {
		return array(
			'_md_video_url' => array( 'Vidéo', 'media', 'Envoyez la vidéo (MP4, de préférence moins de 30 Mo) ou collez un lien YouTube / TikTok / Instagram / Facebook.' ),
			'_md_video_lieu' => array( 'Lieu ou type de chantier', 'text', 'Ex. Appartement, Ouakam · Bureaux, Plateau' ),
		);
	}
	return array(
		'_md_t_quote'   => array( 'Témoignage', 'textarea', 'Les mots du client, tels qu\'il les a dits ou écrits.' ),
		'_md_t_role'    => array( 'Quartier, fonction ou entreprise', 'text', 'Ex. Particulier, Mermoz · Gérante de boutique, Liberté 6' ),
		'_md_t_service' => array( 'Prestation ou produit concerné', 'text', 'Ex. Grand ménage · NDOMBO · Formation' ),
		'_md_t_note'    => array( 'Note donnée par le client (facultatif)', 'note', 'Laissez vide si le client n\'a pas donné de note.' ),
		'_md_t_video'   => array( 'Vidéo du témoignage (facultatif)', 'media', 'Fichier vidéo ou lien YouTube / TikTok.' ),
	);
}

add_action( 'add_meta_boxes', function () {
	foreach ( array( 'md_video' => 'Vidéo du chantier', 'md_temoignage' => 'Témoignage' ) as $type => $title ) {
		add_meta_box( 'md_proof', $title, 'md_proof_metabox', $type, 'normal', 'high' );
	}
} );

function md_proof_metabox( $post ) {
	wp_nonce_field( 'md_proof_save', 'md_proof_nonce' );
	echo '<p style="margin:0 0 14px;padding:10px 12px;background:#fff8e5;border-left:4px solid #D9B25A">Uniquement du contenu réel : vraies vidéos de vos chantiers, vrais clients avec leur accord. ';
	echo 'md_video' === $post->post_type ? 'L\'« Image mise en avant » sert d\'aperçu de la vidéo (conseillé).' : 'L\'« Image mise en avant » est la photo du client ou du chantier (facultatif).';
	echo ' Le champ « Ordre » (Attributs) règle l\'ordre d\'affichage.</p>';
	foreach ( md_proof_fields( $post->post_type ) as $key => $f ) {
		$v = get_post_meta( $post->ID, $key, true );
		echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $f[0] ) . '</strong></label><br>';
		if ( 'textarea' === $f[1] ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="5" style="width:100%">' . esc_textarea( $v ) . '</textarea>';
		} elseif ( 'note' === $f[1] ) {
			echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '"><option value="">Pas de note</option>';
			for ( $i = 5; $i >= 1; $i-- ) { echo '<option value="' . $i . '"' . selected( (int) $v, $i, false ) . '>' . $i . ' / 5</option>'; }
			echo '</select>';
		} elseif ( 'media' === $f[1] ) {
			echo '<span style="display:flex;gap:8px"><input type="url" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $v ) . '" style="flex:1" placeholder="https://">';
			echo '<button type="button" class="button md-pick" data-target="' . esc_attr( $key ) . '">Choisir une vidéo</button></span>';
		} else {
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $v ) . '" style="width:100%">';
		}
		echo '<span class="description">' . esc_html( $f[2] ) . '</span></p>';
	}
}

add_action( 'save_post', function ( $post_id, $post ) {
	if ( ! in_array( $post->post_type, array( 'md_video', 'md_temoignage' ), true ) ) { return; }
	if ( ! isset( $_POST['md_proof_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['md_proof_nonce'] ), 'md_proof_save' ) ) { return; }
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
	foreach ( md_proof_fields( $post->post_type ) as $key => $f ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore -- assaini ci-dessous
		if ( 'media' === $f[1] ) {
			$v = esc_url_raw( trim( $raw ) );
		} elseif ( 'textarea' === $f[1] ) {
			$v = sanitize_textarea_field( $raw );
		} elseif ( 'note' === $f[1] ) {
			$v = $raw ? min( 5, max( 1, absint( $raw ) ) ) : '';
		} else {
			$v = sanitize_text_field( $raw );
		}
		if ( '' === $v ) { delete_post_meta( $post_id, $key ); } else { update_post_meta( $post_id, $key, $v ); }
	}
}, 10, 2 );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, array( 'md_video', 'md_temoignage' ), true ) ) { return; }
	wp_enqueue_media();
	wp_add_inline_script( 'media-editor', "jQuery(function($){\$(document).on('click','.md-pick',function(e){e.preventDefault();var t=\$('#'+\$(this).data('target'));var f=wp.media({title:'Choisir une vidéo',library:{type:'video'},button:{text:'Utiliser cette vidéo'},multiple:false});f.on('select',function(){t.val(f.state().get('selection').first().toJSON().url);});f.open();});});" );
} );

/* Colonnes utiles dans la liste de l'administration */
add_filter( 'manage_md_temoignage_posts_columns', function ( $c ) { $c['md_role'] = 'Quartier / entreprise'; return $c; } );
add_action( 'manage_md_temoignage_posts_custom_column', function ( $col, $id ) { if ( 'md_role' === $col ) { echo esc_html( get_post_meta( $id, '_md_t_role', true ) ); } }, 10, 2 );
add_filter( 'manage_md_video_posts_columns', function ( $c ) { $c['md_lieu'] = 'Lieu'; return $c; } );
add_action( 'manage_md_video_posts_custom_column', function ( $col, $id ) { if ( 'md_lieu' === $col ) { echo esc_html( get_post_meta( $id, '_md_video_lieu', true ) ); } }, 10, 2 );

/* ---------- Affichage ---------- */

/** Vrai fichier vidéo (lecture native) ou lien à intégrer. */
function md_is_video_file( $url ) {
	return (bool) preg_match( '/\.(mp4|webm|mov|m4v)(\?.*)?$/i', (string) $url );
}

/** Lecteur intégré pour un lien YouTube, TikTok ou autre (oEmbed mis en cache). */
function md_embed_html( $url ) {
	$frame = '';
	$vert  = (bool) preg_match( '~(shorts/|tiktok\.com|instagram\.com/reel)~', $url );
	if ( preg_match( '~(?:youtube\.com/(?:shorts/|watch\?v=|embed/)|youtu\.be/)([\w-]{11})~', $url, $m ) ) {
		$frame = 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&rel=0&playsinline=1';
	} elseif ( preg_match( '~tiktok\.com/.*/video/(\d+)~', $url, $m ) ) {
		$frame = 'https://www.tiktok.com/player/v1/' . $m[1] . '?autoplay=1&rel=0';
	}
	if ( $frame ) {
		return '<iframe' . ( $vert ? ' class="v"' : '' ) . ' src="' . esc_url( $frame ) . '" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen title="Vidéo"></iframe>';
	}
	$key  = 'md_oe_' . md5( $url );
	$html = get_transient( $key );
	if ( false === $html ) {
		$html = (string) wp_oembed_get( $url );
		set_transient( $key, $html, WEEK_IN_SECONDS );
	}
	return ( $html && false !== strpos( $html, '<iframe' ) ) ? $html : '';
}

/** Attributs data pour ouvrir une vidéo dans la visionneuse. */
function md_video_attrs( $url, $title ) {
	if ( md_is_video_file( $url ) ) {
		return ' data-video="' . esc_url( $url ) . '" data-title="' . esc_attr( $title ) . '"';
	}
	$embed = md_embed_html( $url );
	if ( $embed ) {
		return ' data-embed="' . esc_attr( $embed ) . '" data-title="' . esc_attr( $title ) . '"';
	}
	return ' data-link="' . esc_url( $url ) . '"';
}

/** Section « Nos chantiers, en vrai » : vidéos puis témoignages. */
function md_proof_section( $theme = 'dark' ) {
	$videos = get_posts( array( 'post_type' => 'md_video', 'post_status' => 'publish', 'numberposts' => 12, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	$videos = array_filter( $videos, function ( $v ) { return (bool) get_post_meta( $v->ID, '_md_video_url', true ); } );
	$quotes = get_posts( array( 'post_type' => 'md_temoignage', 'post_status' => 'publish', 'numberposts' => 9, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	$quotes = array_filter( $quotes, function ( $q ) { return (bool) get_post_meta( $q->ID, '_md_t_quote', true ); } );

	if ( ! $videos && ! $quotes ) {
		if ( current_user_can( 'edit_posts' ) ) {
			echo '<section class="sec proof"><div class="wrap"><div class="proof-admin">';
			echo '<b>Section « Nos chantiers, en vrai » — visible par vous seul tant qu\'elle est vide.</b>';
			echo '<span>Ajoutez vos vraies vidéos de nettoyage et les témoignages de vos clients : la section apparaîtra automatiquement pour les visiteurs.</span>';
			echo '<span class="cta"><a class="btn btn-gold" href="' . esc_url( admin_url( 'post-new.php?post_type=md_video' ) ) . '">Ajouter une vidéo</a><a class="btn btn-ghost" href="' . esc_url( admin_url( 'post-new.php?post_type=md_temoignage' ) ) . '">Ajouter un témoignage</a></span>';
			echo '</div></div></section>';
		}
		return;
	}

	echo '<section class="sec proof" id="realisations"><div class="wrap">';
	echo '<div class="proof-h"><div><div class="eyebrow rv">Nos chantiers, en vrai</div>';
	echo '<h2 class="h2 rv d1" style="margin-top:12px;max-width:760px">' . ( $videos ? 'Ne nous croyez pas sur parole. <em>Regardez.</em>' : 'Ce que disent nos clients' ) . '</h2></div>';
	if ( count( $videos ) > 2 ) {
		echo '<div class="rail-nav rv"><button type="button" class="rail-b" data-rail="-1" aria-label="Vidéos précédentes">' . md_icon( 'arr' ) . '</button><button type="button" class="rail-b" data-rail="1" aria-label="Vidéos suivantes">' . md_icon( 'arr' ) . '</button></div>';
	}
	echo '</div>';

	if ( $videos ) {
		echo '<div class="rail" tabindex="0" aria-label="Vidéos de nos chantiers">';
		$i = 0;
		foreach ( $videos as $v ) {
			$url    = get_post_meta( $v->ID, '_md_video_url', true );
			$lieu   = get_post_meta( $v->ID, '_md_video_lieu', true );
			$title  = get_the_title( $v );
			$poster = get_the_post_thumbnail_url( $v, 'large' );
			$d      = $i % 4 ? ' d' . ( $i % 4 ) : '';
			echo '<button type="button" class="vcard rv' . esc_attr( $d ) . '"' . md_video_attrs( $url, $title ) . ' aria-label="Lire la vidéo : ' . esc_attr( $title ) . '">'; // phpcs:ignore -- attributs échappés
			if ( md_is_video_file( $url ) ) {
				echo '<video muted playsinline loop preload="' . ( $poster ? 'none' : 'metadata' ) . '"' . ( $poster ? ' poster="' . esc_url( $poster ) . '"' : '' ) . ' src="' . esc_url( $url . ( $poster ? '' : '#t=0.5' ) ) . '"></video>';
			} elseif ( $poster ) {
				echo '<img src="' . esc_url( $poster ) . '" alt="" loading="lazy">';
			}
			echo '<span class="vplay">' . md_icon( 'play', '' ) . '</span>';
			echo '<span class="vtxt"><small>Vidéo réelle</small><b>' . esc_html( $title ) . '</b>' . ( $lieu ? '<em>' . esc_html( $lieu ) . '</em>' : '' ) . '</span>';
			echo '</button>';
			$i++;
		}
		echo '</div>';
	}

	if ( $quotes ) {
		if ( $videos ) { echo '<h3 class="proof-sub rv">Ce que disent nos clients</h3>'; }
		echo '<div class="quotes">';
		$i = 0;
		foreach ( $quotes as $q ) {
			$name    = get_the_title( $q );
			$role    = get_post_meta( $q->ID, '_md_t_role', true );
			$service = get_post_meta( $q->ID, '_md_t_service', true );
			$note    = (int) get_post_meta( $q->ID, '_md_t_note', true );
			$vid     = get_post_meta( $q->ID, '_md_t_video', true );
			$photo   = get_the_post_thumbnail_url( $q, 'thumbnail' );
			$d       = $i % 3 ? ' d' . ( $i % 3 ) : '';
			echo '<figure class="quote rv' . esc_attr( $d ) . '">';
			if ( $service ) { echo '<span class="qsvc">' . esc_html( $service ) . '</span>'; }
			if ( $note ) { echo '<span class="stars" role="img" aria-label="Note : ' . (int) $note . ' sur 5">' . str_repeat( '★', $note ) . '<i>' . str_repeat( '★', 5 - $note ) . '</i></span>'; }
			echo '<blockquote>' . nl2br( esc_html( get_post_meta( $q->ID, '_md_t_quote', true ) ) ) . '</blockquote>';
			echo '<figcaption>';
			echo $photo ? '<img src="' . esc_url( $photo ) . '" alt="" width="48" height="48" loading="lazy">' : '<span class="mono" aria-hidden="true">' . esc_html( mb_strtoupper( mb_substr( $name, 0, 1 ) ) ) . '</span>';
			echo '<span><b>' . esc_html( $name ) . '</b>' . ( $role ? '<small>' . esc_html( $role ) . '</small>' : '' ) . '</span>';
			if ( $vid ) { echo '<button type="button" class="qplay"' . md_video_attrs( $vid, 'Témoignage de ' . $name ) . ' aria-label="Voir le témoignage vidéo de ' . esc_attr( $name ) . '">' . md_icon( 'play', '' ) . '</button>'; } // phpcs:ignore
			echo '</figcaption></figure>';
			$i++;
		}
		echo '</div>';
	}

	echo '<div class="cta rv" style="margin-top:34px"><a class="btn btn-gold" href="' . esc_url( md_wa( 'Bonjour, j\'ai vu vos vidéos de chantier. Je souhaite un devis.' ) ) . '" target="_blank" rel="noopener">' . md_icon( 'wa' ) . 'Je veux le même résultat</a></div>';
	echo '</div></section>';
	md_lightbox_once();
}

/** Visionneuse vidéo, imprimée une seule fois par page. */
function md_lightbox_once() {
	static $done = false;
	if ( $done ) { return; }
	$done = true;
	echo '<dialog class="vbox" id="vbox" aria-label="Lecture de la vidéo"><button type="button" class="vbox-x" aria-label="Fermer la vidéo">' . md_icon( 'no', '' ) . '</button><div class="vbox-in"></div></dialog>';
}

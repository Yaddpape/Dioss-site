<?php get_header(); ?>
<?php
if ( is_category() ) {
	md_page_hero( 'Blog', single_cat_title( '', false ), wp_strip_all_tags( category_description() ) ?: 'Conseils d\'entretien et d\'entrepreneur par Monsieur Dioss.', 'salon.jpg' );
} elseif ( is_search() ) {
	md_page_hero( 'Recherche', 'Résultats pour « ' . get_search_query() . ' »', '', 'salon.jpg' );
} else {
	md_page_hero( 'Blog', 'Conseils d\'entretien et d\'entrepreneur', 'Astuces de nettoyage testées sur nos chantiers, entretien de la maison et des bureaux, et conseils pour créer votre entreprise de produits d\'entretien.', 'salon.jpg' );
}
$cats = get_categories( array( 'hide_empty' => true ) );
?>
<section class="sec light">
<div class="wrap">
<?php if ( $cats ) : ?>
<nav class="cats" aria-label="Catégories du blog" style="margin:0 0 8px">
<a href="<?php echo esc_url( md_blog_url() ); ?>"<?php echo is_home() ? ' class="on"' : ''; ?>>Tous les articles</a>
<?php foreach ( $cats as $c ) : if ( 'uncategorized' === $c->slug || 'non-classe' === $c->slug ) { continue; } ?>
<a href="<?php echo esc_url( get_category_link( $c ) ); ?>"<?php echo is_category( $c->term_id ) ? ' class="on"' : ''; ?>><?php echo esc_html( $c->name ); ?></a>
<?php endforeach; ?>
</nav>
<?php endif; ?>
<?php if ( have_posts() ) : ?>
<div class="posts">
<?php $i = 0; while ( have_posts() ) { the_post(); md_post_card( $i++ ); } ?>
</div>
<div class="pager"><?php echo wp_kses_post( paginate_links( array( 'prev_text' => '‹', 'next_text' => '›' ) ) ); ?></div>
<?php else : ?>
<p class="empty">Aucun article pour le moment. Revenez bientôt !</p>
<?php endif; ?>
</div>
</section>
<?php get_footer(); ?>

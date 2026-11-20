<?php get_header(); ?>
<?php md_page_hero( 'Page introuvable', 'Cette page n\'existe pas (ou plus).', 'Pas de panique : voici où trouver ce que vous cherchez.', 'eclaboussure.jpg' ); ?>
<section class="sec light"><div class="wrap">
<div class="cta">
<a class="btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l'accueil</a>
<a class="btn btn-dark" href="<?php echo esc_url( md_shop_url() ); ?>">Boutique</a>
<a class="btn btn-dark" href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>">Nettoyage</a>
<a class="btn btn-dark" href="<?php echo esc_url( md_blog_url() ); ?>">Blog</a>
</div>
</div></section>
<?php get_footer(); ?>

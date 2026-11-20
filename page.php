<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php
$md_is_wc_page = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );
$md_elementor  = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
if ( $md_elementor ) :
	the_content();
else :
?>
<section class="page-hero" style="padding:clamp(40px,6vw,64px) 0"><div class="bg"><img src="<?php echo md_img( 'salon.jpg' ); ?>" alt=""></div><div class="wrap">
<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <?php the_title(); ?></div>
<h1><?php the_title(); ?></h1>
</div></section>
<section class="sec light" style="padding-top:clamp(36px,5vw,56px)"><div class="wrap"><div class="<?php echo $md_is_wc_page ? 'woocommerce' : 'prose'; ?>"><?php the_content(); ?></div></div></section>
<?php endif; ?>
<?php endwhile; ?>
<?php get_footer(); ?>

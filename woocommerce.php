<?php get_header(); ?>
<?php if ( is_shop() || is_product_taxonomy() ) { md_shop_hero(); } ?>
<section class="woo-wrap light<?php echo is_product() ? ' is-product' : ''; ?>">
<div class="wrap woocommerce">
<?php woocommerce_content(); ?>
</div>
</section>
<?php get_footer(); ?>

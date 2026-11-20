<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); $cat = get_the_category(); ?>
<article <?php post_class(); ?>>
<section class="page-hero"><div class="bg"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large', array( 'alt' => '' ) ); } else { echo '<img src="' . md_img( 'salon.jpg' ) . '" alt="">'; } ?></div><div class="wrap">
<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <a href="<?php echo esc_url( md_blog_url() ); ?>">Blog</a><?php if ( $cat ) : ?> / <a href="<?php echo esc_url( get_category_link( $cat[0] ) ); ?>"><?php echo esc_html( $cat[0]->name ); ?></a><?php endif; ?></div>
<h1><?php the_title(); ?></h1>
<div class="meta"><span>Par Monsieur Dioss</span><span><?php echo esc_html( get_the_date() ); ?></span><span><?php echo (int) md_reading_time(); ?> min de lecture</span></div>
</div></section>
<section class="sec light">
<div class="wrap">
<div class="prose"><?php the_content(); ?></div>
<?php $cta = md_post_cta(); ?>
<div class="post-cta">
<h3><?php echo esc_html( $cta[0] ); ?></h3>
<p><?php echo esc_html( $cta[1] ); ?></p>
<div class="cta"><a class="btn <?php echo $cta[4] ? 'btn-wa' : 'btn-gold'; ?>" href="<?php echo esc_url( $cta[2] ); ?>"<?php echo $cta[4] ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo $cta[4] ? md_icon( 'wa' ) : ''; ?><?php echo esc_html( $cta[3] ); ?></a></div>
</div>
<?php
$rel = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ), 'category__in' => wp_list_pluck( $cat, 'term_id' ), 'ignore_sticky_posts' => true ) );
if ( $rel->have_posts() ) :
?>
<h2 class="h2" style="font-size:clamp(24px,3vw,32px);margin-top:64px">À lire aussi</h2>
<div class="posts"><?php $i = 0; while ( $rel->have_posts() ) { $rel->the_post(); md_post_card( $i++ ); } wp_reset_postdata(); ?></div>
<?php endif; ?>
</div>
</section>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>

<?php get_header(); ?>
<?php md_page_hero( 'Contact', 'Parlons de votre besoin', 'Devis de nettoyage, commande de produits, revente ou formation : écrivez-nous, nous répondons rapidement.', 'bureau-direction.jpg' ); ?>
<section class="sec light">
<div class="wrap">
<?php md_contact_cards(); ?>
<div class="cta rv" style="margin-top:28px">
<a class="btn btn-wa" href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite un devis pour une prestation de nettoyage.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Devis nettoyage</a>
<a class="btn btn-dark" href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite passer une commande de produits.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Commande produits</a>
<a class="btn btn-dark" href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite devenir revendeur Monsieur Dioss.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Devenir revendeur</a>
<a class="btn btn-dark" href="<?php echo esc_url( md_wa( 'Bonjour, j\'ai une question sur la formation Monsieur Dioss.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Formation</a>
</div>

</div>
</section>
<?php get_footer(); ?>

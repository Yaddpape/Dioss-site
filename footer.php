<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
</main>
<?php if ( ! is_page( 'formation' ) ) : ?><div class="band"><div class="wrap"><h2>Un chantier, une commande, une question ?</h2><a class="btn btn-dark" href="<?php echo esc_url( md_wa( 'Bonjour Monsieur Dioss, j\'ai une question.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Écrire sur WhatsApp</a></div></div><?php endif; ?>
<footer>
<div class="wrap fgrid">
<div>
<div class="logo" style="margin-bottom:12px"><img src="<?php echo md_img( 'logo-mascotte.jpg' ); ?>" alt="" width="44" height="44"><span><span class="logo-t">MONSIEUR DIOSS</span><br><span class="logo-s">LE PATRON DE LA PROPRETÉ</span></span></div>
<p style="margin:0 0 12px;max-width:320px">Fabricant de produits d'entretien, services de nettoyage et formation à Dakar.</p>
<p style="margin:0">Tél. / WhatsApp : <?php echo esc_html( md_opt( 'phone_display' ) ); ?><br><?php echo esc_html( md_opt( 'email' ) ); ?></p>
<?php
$socials = array_filter( array( 'Facebook' => md_opt( 'facebook' ), 'Instagram' => md_opt( 'instagram' ), 'TikTok' => md_opt( 'tiktok' ) ) );
if ( $socials ) {
	echo '<p style="margin:12px 0 0;display:flex;gap:14px">';
	foreach ( $socials as $n => $u ) { echo '<a style="display:inline" href="' . esc_url( $u ) . '" target="_blank" rel="noopener">' . esc_html( $n ) . '</a>'; }
	echo '</p>';
}
?>
</div>
<div><h4>Nettoyage</h4><a href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>#grand-menage">Grand ménage</a><a href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>#abonnement">Abonnement entreprises</a><a href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>#detartrage">Détartrage WC &amp; carreaux</a></div>
<div><h4>Boutique</h4><a href="<?php echo esc_url( md_shop_url() ); ?>">Tous les produits</a><a href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite devenir revendeur Monsieur Dioss.' ) ); ?>" target="_blank" rel="noopener">Devenir revendeur</a><?php if ( function_exists( 'wc_get_cart_url' ) ) : ?><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Mon panier</a><?php endif; ?></div>
<div><h4>Académie &amp; Blog</h4><a href="<?php echo esc_url( md_page_url( 'formation' ) ); ?>">Formation fabricant</a><a href="<?php echo esc_url( md_blog_url() ); ?>">Blog &amp; conseils</a><a href="<?php echo esc_url( md_page_url( 'a-propos' ) ); ?>">À propos</a><a href="<?php echo esc_url( md_page_url( 'contact' ) ); ?>">Contact</a></div>
</div>
<div class="wrap fmark" aria-hidden="true"><svg viewBox="0 0 1200 150" preserveAspectRatio="xMidYMid meet"><text x="600" y="128" text-anchor="middle" textLength="1196" lengthAdjust="spacingAndGlyphs">MONSIEUR DIOSS</text></svg></div>
<div class="wrap fbot"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Monsieur Dioss. Tous droits réservés.</span><span>Produits chimiques : gants, lunettes, lieu aéré, hors de portée des enfants.</span></div>
</footer>
<a class="wa-float" href="<?php echo esc_url( md_wa( 'Bonjour Monsieur Dioss !' ) ); ?>" target="_blank" rel="noopener" aria-label="Écrire à Monsieur Dioss sur WhatsApp"><?php echo md_icon( 'wa', '' ); ?></a>
<?php wp_footer(); ?>
</body>
</html>

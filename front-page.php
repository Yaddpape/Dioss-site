<?php get_header(); ?>
<section class="hero">
<div class="hero-bg"><img class="kb" src="<?php echo md_img( 'salon.jpg' ); ?>" alt="" fetchpriority="high"><div class="glass" aria-hidden="true"><i class="fog"></i><i class="gleam"></i><i class="blade"></i></div><i class="spark s1" aria-hidden="true"></i><i class="spark s2" aria-hidden="true"></i><i class="spark s3" aria-hidden="true"></i></div><div class="hero-sh"></div>
<div class="wrap"><div class="hero-in">
<div class="eyebrow rv">Monsieur Dioss · Sénégal</div>
<h1 class="rv d1">La propreté, <em>version professionnelle.</em></h1>
<p class="lead rv d2">Nous fabriquons nos propres produits d'entretien, nous nettoyons maisons et entreprises à Dakar, et nous formons les entrepreneurs qui veulent se lancer dans le secteur.</p>
<div class="cta rv d2"><a class="btn btn-gold" href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>">Demander un devis nettoyage</a><a class="btn btn-ghost" href="<?php echo esc_url( md_shop_url() ); ?>">Acheter nos produits</a></div>
<div class="pills rv d3"><span class="pill">Fabriqué au Sénégal</span><span class="pill">Livraison <?php echo esc_html( md_opt( 'zone' ) ); ?></span><span class="pill">Wave · Orange Money</span></div>
</div></div>
<div class="seal" aria-hidden="true"><svg viewBox="0 0 200 200"><defs><path id="md-seal" d="M100 100m-80 0a80 80 0 1 1 160 0a80 80 0 1 1-160 0"/></defs><text><textPath href="#md-seal" textLength="500" lengthAdjust="spacing">LE PATRON DE LA PROPRETÉ ✦ FABRIQUÉ AU SÉNÉGAL ✦ </textPath></text></svg><img src="<?php echo md_img( 'logo-mascotte.jpg' ); ?>" alt="" width="120" height="120"></div>
</section>

<?php
$md_sig = array( 'La qualité n\'est jamais un hasard.', 'Créez. Fabriquez. Vendez. Développez.', 'Une entreprise solide commence par un bon produit.', 'Fabriqué au Sénégal' );
$md_grp = '';
foreach ( $md_sig as $sig ) { $md_grp .= '<span>' . esc_html( $sig ) . '</span><i>✦</i>'; }
?>
<div class="ticker"><div class="ticker-t"><div class="grp"><?php echo $md_grp; // phpcs:ignore -- échappé ci-dessus ?></div><div class="grp" aria-hidden="true"><?php echo $md_grp; // phpcs:ignore ?></div></div></div>

<?php
$md_manif = 'Nous *fabriquons* nos propres produits. Nos équipes les utilisent *chaque* *jour* sur le terrain. Et nous *transmettons* notre méthode à ceux qui veulent se lancer.';
$md_words = '';
foreach ( explode( ' ', $md_manif ) as $w ) {
	$g = '*' === substr( $w, 0, 1 );
	$md_words .= '<span' . ( $g ? ' class="g"' : '' ) . '>' . esc_html( trim( $w, '*' ) ) . '</span> ';
}
?>
<section class="manif">
<div class="wrap">
<div class="eyebrow rv">Notre manifeste</div>
<p class="manif-t" data-manif><?php echo $md_words; // phpcs:ignore -- échappé ci-dessus ?></p>
<div class="manif-s rv">Monsieur Dioss · Le patron de la propreté</div>
</div>
</section>

<section class="sec">
<div class="wrap">
<div class="eyebrow rv">Trois métiers, une exigence</div>
<h2 class="h2 rv d1" style="margin-top:12px;max-width:760px">Que voulez-vous faire aujourd'hui ?</h2>
<div class="poles">
<a class="pole rv" href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>"><img src="<?php echo md_img( 'bureaux.jpg' ); ?>" alt="" loading="lazy"><div class="c"><div class="k">01 · SERVICE</div><h3>Nettoyage professionnel</h3><p>Maisons, bureaux, commerces : abonnements réguliers ou grand ménage en une journée.</p><span class="go">Demander un devis <?php echo md_icon( 'arr' ); ?></span></div></a>
<a class="pole rv d1" href="<?php echo esc_url( md_shop_url() ); ?>"><img src="<?php echo md_img( 'ndombo.jpg' ); ?>" alt="" loading="lazy"><div class="c"><div class="k">02 · BOUTIQUE</div><h3>Produits d'entretien</h3><p>Nettoyant carreaux &amp; WC, NDOMBO, BENET : la gamme Monsieur Dioss livrée chez vous.</p><span class="go">Voir la boutique <?php echo md_icon( 'arr' ); ?></span></div></a>
<a class="pole rv d2" href="<?php echo esc_url( md_page_url( 'formation' ) ); ?>"><img src="<?php echo md_img( 'labo.jpg' ); ?>" alt="" loading="lazy"><div class="c"><div class="k">03 · ACADÉMIE</div><h3>Formation fabricant</h3><p>Lancez votre entreprise de produits de nettoyage avec nos formulations et notre méthode.</p><span class="go">Découvrir la formation <?php echo md_icon( 'arr' ); ?></span></div></a>
</div>
</div>
</section>

<section class="sec light" id="boutique">
<div class="wrap">
<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:16px">
<div><div class="eyebrow rv">La boutique</div><h2 class="h2 rv d1" style="margin-top:12px">Nos produits phares</h2></div>
<a class="btn btn-dark rv" href="<?php echo esc_url( md_shop_url() ); ?>">Toute la boutique <?php echo md_icon( 'arr' ); ?></a>
</div>
<?php md_products_grid( 4 ); ?>
<p class="rv" style="margin:22px 0 0;color:#3E4A60;font-size:14.5px">Commerçants et revendeurs : prix par carton sur demande. <a href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite devenir revendeur Monsieur Dioss.' ) ); ?>" target="_blank" rel="noopener" style="color:#7A5A12;font-weight:700">Devenir revendeur</a></p>
</div>
</section>

<section class="gband">
<img class="gband-img" data-parallax src="<?php echo md_img( 'gamme.jpg' ); ?>" alt="La gamme de produits d'entretien Monsieur Dioss" loading="lazy">
<div class="wrap">
<div class="eyebrow rv">La gamme Monsieur Dioss</div>
<h2 class="rv d1">Formulée, fabriquée et testée <em>au Sénégal.</em></h2>
<p class="lead rv d2">Chaque produit passe d'abord par nos propres chantiers avant d'arriver chez vous.</p>
<div class="cta rv d3"><a class="btn btn-gold" href="<?php echo esc_url( md_shop_url() ); ?>">Découvrir la gamme <?php echo md_icon( 'arr' ); ?></a></div>
</div>
</section>

<section class="sec" id="nettoyage" style="background:var(--navy)">
<div class="wrap">
<div class="eyebrow rv">Services de nettoyage</div>
<h2 class="h2 rv d1" style="margin-top:12px;max-width:780px">Des équipes formées, nos propres produits, un résultat contrôlé.</h2>
<?php md_services_cards(); ?>
<div class="cta rv" style="margin-top:30px"><a class="btn btn-gold" href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite un devis pour une prestation de nettoyage.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Demander un devis sur WhatsApp</a><a class="btn btn-ghost" href="<?php echo esc_url( md_page_url( 'nettoyage' ) ); ?>">Voir nos prestations</a></div>
</div>
</section>

<section class="sec light">
<div class="wrap">
<div class="eyebrow rv">Comment ça se passe</div>
<h2 class="h2 rv d1" style="margin-top:12px">Un chantier propre, de A à Z</h2>
<?php md_steps(); ?>
</div>
</section>

<?php md_proof_section(); ?>

<section class="sec" id="formation">
<div class="wrap form">
<div>
<div class="eyebrow rv">Monsieur Dioss Académie</div>
<h2 class="h2 rv d1" style="margin-top:12px">Devenez fabricant de produits d'entretien</h2>
<p class="lead rv d2" style="margin-top:14px">5 formulations professionnelles, protocoles, contrôle qualité, coût de revient, marque et plan commercial : la méthode d'une marque qui fabrique et vend déjà.</p>
<div class="offers-mini rv d2">
<div class="om"><small>OFFRE 1</small><b>Manuel Professionnel</b><span>99 000 F</span></div>
<div class="om" style="border-color:var(--gold)"><small>OFFRE 2</small><b>Business Academy</b><span>149 000 F</span></div>
<div class="om"><small>OFFRE 3</small><b>Business Elite</b><span>299 000 F</span></div>
</div>
<div class="cta rv d3"><a class="btn btn-gold" href="<?php echo esc_url( md_page_url( 'formation' ) ); ?>">Découvrir la formation</a></div>
</div>
<div class="ph rv d1"><img src="<?php echo md_img( 'qualite.jpg' ); ?>" alt="Contrôle qualité d'un produit en laboratoire" loading="lazy"></div>
</div>
</section>

<section class="sec" style="background:var(--navy)">
<div class="wrap">
<div class="eyebrow rv">Pourquoi Monsieur Dioss</div>
<h2 class="h2 rv d1" style="margin-top:12px;max-width:760px">« Une entreprise solide commence par un bon produit. »</h2>
<div class="why">
<div class="w rv"><h3>Fabricant local</h3><p>Nos produits sont formulés et fabriqués au Sénégal, pour les réalités de nos foyers.</p></div>
<div class="w rv d1"><h3>Testé sur nos chantiers</h3><p>Nos équipes de nettoyage utilisent exclusivement nos produits, chaque jour.</p></div>
<div class="w rv d2"><h3>Personnel encadré</h3><p>Agents formés, superviseur sur place, respect de vos biens et de vos délais.</p></div>
<div class="w rv d3"><h3>Prix justes</h3><p>Des produits efficaces à prix accessibles, et des tarifs revendeurs par carton.</p></div>
</div>
</div>
</section>

<?php
$md_posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ) );
if ( $md_posts->have_posts() ) :
?>
<section class="sec light" id="blog">
<div class="wrap">
<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:16px">
<div><div class="eyebrow rv">Le blog Monsieur Dioss</div><h2 class="h2 rv d1" style="margin-top:12px">Conseils d'entretien et d'entrepreneur</h2></div>
<a class="btn btn-dark rv" href="<?php echo esc_url( md_blog_url() ); ?>">Tous les articles <?php echo md_icon( 'arr' ); ?></a>
</div>
<div class="posts">
<?php $i = 0; while ( $md_posts->have_posts() ) { $md_posts->the_post(); md_post_card( $i++ ); } wp_reset_postdata(); ?>
</div>
</div>
</section>
<?php endif; ?>

<section class="sec light" id="contact">
<div class="wrap">
<div class="eyebrow rv">Contact</div>
<h2 class="h2 rv d1" style="margin-top:12px">Parlons de votre besoin</h2>
<?php md_contact_cards(); ?>
</div>
</section>
<?php get_footer(); ?>

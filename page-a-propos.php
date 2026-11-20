<?php get_header(); ?>
<?php md_page_hero( 'À propos', 'Monsieur Dioss, le patron de la propreté', 'Une marque sénégalaise qui fabrique ses produits d\'entretien, nettoie maisons et entreprises, et transmet son savoir-faire aux entrepreneurs.', 'labo.jpg' ); ?>
<section class="sec light">
<div class="wrap form">
<div>
<div class="eyebrow rv">Notre histoire</div>
<h2 class="h2 rv d1" style="margin-top:12px">Né d'un problème du quotidien</h2>
<p class="lead rv d2" style="margin-top:14px">Monsieur Dioss est né d'une idée simple : proposer au Sénégal un nettoyage rapide et en profondeur des toilettes entartrées. Nous avons d'abord créé nos produits, puis nos équipes de nettoyage, et aujourd'hui une formation pour celles et ceux qui veulent devenir fabricants.</p>
<p class="lead rv d3" style="margin-top:14px">Nos équipes utilisent chaque jour les produits que nous vendons : chaque formule est éprouvée sur le terrain avant d'arriver chez vous.</p>
</div>
<div class="ph rv d1"><img src="<?php echo md_img( 'nettoyant-carreaux.jpg' ); ?>" alt="Nettoyant carreaux et WC Monsieur Dioss" loading="lazy" style="object-position:center 40%"></div>
</div>
</section>
<section class="sec" style="background:var(--navy)">
<div class="wrap">
<div class="eyebrow rv">Nos valeurs</div>
<h2 class="h2 rv d1" style="margin-top:12px">« La qualité n'est jamais un hasard. »</h2>
<div class="why">
<div class="w rv"><h3>Qualité</h3><p>Des formules contrôlées, un résultat visible.</p></div>
<div class="w rv d1"><h3>Sécurité</h3><p>Des consignes claires pour nos clients et nos équipes.</p></div>
<div class="w rv d2"><h3>Proximité</h3><p>Un produit local, pensé pour les foyers africains.</p></div>
<div class="w rv d3"><h3>Transmission</h3><p>Former les fabricants de demain.</p></div>
</div>
</div>
</section>
<?php while ( have_posts() ) : the_post(); if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
<section class="sec light"><div class="wrap"><div class="prose"><?php the_content(); ?></div></div></section>
<?php endif; endwhile; ?>
<?php get_footer(); ?>

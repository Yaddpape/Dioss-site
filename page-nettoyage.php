<?php get_header(); ?>
<?php md_page_hero( 'Nettoyage', 'Services de nettoyage professionnel à Dakar', 'Maisons, appartements, bureaux, commerces : des équipes formées et encadrées, nos propres produits, un résultat contrôlé en fin de chantier.', 'bureaux.jpg' ); ?>
<section class="sec light">
<div class="wrap">
<div class="eyebrow rv">Nos prestations</div>
<h2 class="h2 rv d1" style="margin-top:12px">Choisissez votre formule</h2>
<div class="cta rv" style="margin:18px 0 0"><a class="btn btn-wa" href="<?php echo esc_url( md_wa( 'Bonjour, je souhaite un devis pour une prestation de nettoyage.' ) ); ?>" target="_blank" rel="noopener"><?php echo md_icon( 'wa' ); ?>Devis gratuit sur WhatsApp</a><a class="btn btn-dark" href="tel:+<?php echo esc_attr( preg_replace( '/\D/', '', md_opt( 'whatsapp' ) ) ); ?>">Appeler le <?php echo esc_html( md_opt( 'phone_display' ) ); ?></a></div>
</div>
</section>
<section class="sec" style="background:var(--navy);padding-top:clamp(40px,6vw,64px)">
<div class="wrap">
<?php md_services_cards(); ?>
</div>
</section>
<section class="sec light">
<div class="wrap">
<div class="eyebrow rv">Comment ça se passe</div>
<h2 class="h2 rv d1" style="margin-top:12px">Un chantier propre, de A à Z</h2>
<?php md_steps(); ?>
</div>
</section>
<section class="sec">
<div class="wrap form">
<div>
<div class="eyebrow rv">Nos engagements</div>
<h2 class="h2 rv d1" style="margin-top:12px">Un service sérieux, du devis à la validation</h2>
<div class="why" style="grid-template-columns:repeat(2,minmax(0,1fr))">
<div class="w rv"><h3>Personnel formé</h3><p>Hygiène, sécurité, respect des biens du client, supervision continue.</p></div>
<div class="w rv d1"><h3>Nos propres produits</h3><p>Formulés et fabriqués par Monsieur Dioss pour les surfaces de nos maisons.</p></div>
<div class="w rv"><h3>Ponctualité</h3><p>Prestations régulières, ponctuelles, post-événement ou post-travaux.</p></div>
<div class="w rv d1"><h3>Contrôle final</h3><p>Photos avant / après et validation de fin de chantier avec vous.</p></div>
</div>
</div>
<div class="ph rv d1"><img src="<?php echo md_img( 'carreaux.jpg' ); ?>" alt="Détartrage de carreaux" loading="lazy"></div>
</div>
</section>
<?php while ( have_posts() ) : the_post(); if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
<section class="sec light"><div class="wrap"><div class="prose"><?php the_content(); ?></div></div></section>
<?php endif; endwhile; ?>
<?php get_footer(); ?>

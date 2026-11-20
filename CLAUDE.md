# Monsieur Dioss — thème WordPress du site monsieurdioss.com

Thème sur mesure (PHP classique, sans constructeur) pour la marque sénégalaise **Monsieur Dioss**,
« le patron de la propreté ». Trois activités : services de nettoyage à Dakar, boutique de produits
d'entretien (WooCommerce) et formation pour devenir fabricant. Plus un blog SEO.

## Déploiement
- Ce dépôt est cloné par Hostinger dans `public_html/wp-content/themes/monsieur-dioss/` (site monsieurdioss.com, compte `u692387964`).
- Un push ne met PAS le site à jour tout seul. Après un push sur `main`, redéploie avec le connecteur Hostinger :
  `hosting_git_deploy-website-repository` (username `u692387964`, domain `monsieurdioss.com`,
  repository_url `https://github.com/Yaddpape/dioss-site.git`, branch `main`, directory `wp-content/themes/monsieur-dioss`).
  Sans connecteur : hPanel → Sites web → monsieurdioss.com → Avancé → Git → Déployer.
- Le thème est **en production** : teste en local avant de pousser, puis vérifie le site en ligne (mobile et ordinateur).
- Aucun secret dans ce dépôt (il est public) : pas de mot de passe, clé API ni jeton.

## Structure
- `functions.php` — supports, scripts, réglages (Personnaliser > Monsieur Dioss), hooks WooCommerce, CTA du blog.
- `inc/parts.php` — blocs réutilisables (grille produits, cartes services, étapes, contacts, en-tête de page).
- `inc/setup.php` — mise en place unique à l'activation (pages, accueil, blog, catégories, brouillons). Versionné par `MD_SETUP_VERSION` : n'incrémente que pour une nouvelle migration, et garde-la idempotente.
- `inc/articles.php` — 8 premiers articles importés en brouillon.
- `inc/realisations.php` — types « Vidéos chantiers » (`md_video`) et « Témoignages » (`md_temoignage`), champs, section `md_proof_section()` (accueil et page Nettoyage) et visionneuse vidéo. Section invisible pour les visiteurs tant qu'elle est vide ; un encadré guide l'administrateur. Contenu réel uniquement.
- `front-page.php`, `page-nettoyage.php`, `page-formation.php`, `page-a-propos.php`, `page-contact.php` — pages par slug.
- `index.php` (blog et catégories), `single.php` (article), `page.php`, `woocommerce.php`, `404.php`.
- `assets/css/main.css` — styles du site. `assets/css/formation.css` — page Formation, toutes les règles préfixées `.lp`.
- `assets/js/main.js` — menu mobile, apparitions au défilement, compteurs, vidéos, projecteur sur les cartes.
- Signature de l'accueil (fin de `main.css`, « coup de raclette ») : vitre ternie balayée par une raclette dorée à l'ouverture (CSS seul, `@property --wx`), sceau rotatif, bandeau de signatures, reflet des boutons dorés. Tout est coupé avec `prefers-reduced-motion`.
- Prestige v2 (fin de `main.css` et `main.js`) : manifeste qui s'allume mot à mot au défilement (`[data-manif]`), bande gamme plein écran en parallaxe (`[data-parallax]`), ligne d'étapes qui se trace (`[data-draw]`), engagements numérotés, navigation transparente sur l'accueil (`.nav.solid` au défilement), grain argentique, signature géante en pied de page, contacts cliquables.
- Boutique (`woocommerce.php`, `md_shop_hero()` dans `functions.php`, fin de `main.css`) : en-tête de marque avec catégories, cartes Ajouter + WhatsApp, prix « 2 500 FCFA » (`pre_option_woocommerce_currency_pos`), fiche produit et blocs Panier/Commande stylés. L'icône panier de l'en-tête est `.nav-r .cart` : ne jamais styler `.cart` seul (c'est aussi le formulaire d'achat WooCommerce). Même piège : `.feat` (image d'article) et `.lp .offer.feat` (offre Formation).
- Référencement (`functions.php`) : description par défaut de l'accueil via le filtre `aioseo_description` (seulement si vide dans All in One SEO) et fiche `LocalBusiness` en JSON-LD, construites à partir de `md_opt()`.
- `assets/img/` — images (générées sur Higgsfield, sans personnes ; photos produits réelles).

## Identité
- Couleurs : bleu nuit `#0A1428`, noir `#07080B`, or `#D9B25A`, papier `#F5F6F8`, WhatsApp `#1FAF5A`.
- Polices : Archivo (titres, 800–900), Inter (texte).
- Ton : expert, calme, exigeant sur la qualité et la sécurité. Phrases courtes. Français.
- Signatures : « La qualité n'est jamais un hasard. », « Une entreprise solide commence par un bon produit. », « Créez. Fabriquez. Vendez. Développez. »

## Règles
- Contacts et liens Chariow : toujours via `md_opt()` (modifiables dans Personnaliser), jamais en dur.
- Liens WhatsApp : `md_wa( 'message' )`.
- Produits : ne jamais inventer de prix ni de produit ; la boutique lit les vrais produits WooCommerce.
- Pas de faux témoignages, faux avis, faux chiffres ni fausses démonstrations présentées comme réelles.
- Accessibilité : contraste AA, cibles ≥ 44 px, vrais `<a>`/`<button>`, `alt` sur les images utiles.
- Mobile d'abord : vérifier à 390 px, aucun débordement horizontal.
- Sécurité des produits chimiques dans les articles : jamais de mélange Javel + acide, gants, aération.

## Tester en local
WordPress + plugin SQLite (`WordPress/sqlite-database-integration`), lien symbolique du thème dans
`wp-content/themes/`, `php -S`, puis captures à 1366 px et 390 px.
- wordpress.org est bloqué depuis les sessions cloud : WordPress via `git clone https://github.com/WordPress/WordPress`,
  WooCommerce via la release GitHub (`github.com/woocommerce/woocommerce/releases/download/<version>/woocommerce.zip`),
  WP-CLI via sa release GitHub (`--allow-root`). Le plugin SQLite est dans `packages/plugin-sqlite-database-integration`
  du dépôt ; remplacer son lien `wp-includes/database` par une copie de `packages/mysql-on-sqlite/src`.

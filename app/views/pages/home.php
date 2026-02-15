<div class="hero-scene text-center text-white">
  <div class="hero-scene-content container">
    <p class="eyebrow mb-2">Traiteur &eacute;v&eacute;nementiel &agrave; Bordeaux</p>
    <h1 class="mb-3">Vite & Gourmand</h1>
    <p class="hero-subtitle mb-4">Des menus sur mesure, un suivi clair, une livraison ma&icirc;tris&eacute;e.</p>
    <a class="btn btn-primary btn-lg px-4" href="/menus">Voir les menus</a>
  </div>
</div>

<section class="container py-4">
  <div class="surface p-4 p-lg-5">
    <div class="row g-4 align-items-center">
      <div class="col-lg-7">
        <h2 class="text-primary mb-3">Pr&eacute;sentation de l'entreprise</h2>
        <p>Install&eacute;s &agrave; Bordeaux depuis 25 ans, nous concevons des menus sur mesure pour vos &eacute;v&eacute;nements priv&eacute;s et professionnels. Notre &eacute;quipe met l'accent sur la qualit&eacute;, la saisonnalit&eacute; et la transparence des ingr&eacute;dients.</p>
        <p class="mb-0">Notre mission : vous offrir une prestation culinaire fiable, chaleureuse et accessible, avec un suivi clair des commandes.</p>
      </div>
      <div class="col-lg-5">
        <img class="w-100 rounded" src="/images/equipe%20cuinis.jpg" alt="equipe cuisine">
      </div>
    </div>
  </div>
</section>

<section class="container py-2">
  <div class="surface surface-dark p-4 p-lg-5">
    <h2 class="text-primary mb-3">Professionnalisme de l'&eacute;quipe</h2>
    <p class="mb-0">Une &eacute;quipe d&eacute;di&eacute;e, des processus ma&icirc;tris&eacute;s et un souci constant de la s&eacute;curit&eacute; alimentaire. Nous g&eacute;rons la production, la logistique et la livraison avec un suivi en temps r&eacute;el de chaque commande.</p>
  </div>
</section>

<section class="container py-4">
  <h2 class="text-primary text-center mb-4">Avis clients valid&eacute;s</h2>
  <div class="row g-3">
    <?php if (empty($reviews)): ?>
      <div class="col-12">
        <div class="surface p-4 text-center">
          <p class="mb-0">Aucun avis valid&eacute; pour le moment.</p>
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($reviews as $review): ?>
        <div class="col-md-6 col-xl-4">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></h5>
                <span class="badge text-bg-light border">Note <?php echo (int)$review['rating']; ?>/5</span>
              </div>
              <p class="card-text mb-0"><?php echo htmlspecialchars($review['comment']); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

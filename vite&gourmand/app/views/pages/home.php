<div class="hero-scene text-center text-white">
  <div class="hero-scene-content">
    <p>Julie et José vous accueillent pour vos événements</p>
    <h1>Vite & Gourmand</h1>
    <a class="btn btn-primary" href="/menus">Découvrir nos menus</a>
  </div>
</div>

<section class="container py-4">
  <div class="row align-items-center">
    <div class="col-lg-6">
      <h2 class="text-primary">Présentation de l'entreprise</h2>
      <p>Installés à Bordeaux depuis 25 ans, nous concevons des menus sur mesure pour vos événements privés et professionnels. Notre équipe met l'accent sur la qualité, la saisonnalité et la transparence des ingrédients.</p>
      <p>Notre mission : vous offrir une prestation culinaire fiable, chaleureuse et accessible, avec un suivi clair des commandes.</p>
    </div>
    <div class="col-lg-6">
      <img class="w-100 rounded" src="/images/vue-salle-interieur.jpg" alt="Salle de r�ception">
    </div>
  </div>
</section>

<section class="bg-black text-white py-4">
  <div class="container">
    <h2 class="text-primary text-center">Professionnalisme de l'équipe</h2>
    <div class="row align-items-center">
      <div class="col-lg-6">
        <img class="w-100 rounded" src="/images/chef cuisinnier.jpeg" alt="Chef">
      </div>
      <div class="col-lg-6">
        <p>Une équipe dédiée, des processus maitrisés et un souci constant de la sécurité alimentaire. Nous gérons la production, la logistique et la livraison avec un suivi en temps réel de chaque commande.</p>
      </div>
    </div>
  </div>
</section>

<section class="container py-4">
  <h2 class="text-primary text-center">Avis clients validés</h2>
  <div class="row">
    <?php if (empty($reviews)): ?>
      <p class="text-center">Aucun avis validé pour le moment.</p>
    <?php else: ?>
      <?php foreach ($reviews as $review): ?>
        <div class="col-md-4 p-2">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></h5>
              <p class="card-text">Note: <?php echo (int)$review['rating']; ?>/5</p>
              <p class="card-text"><?php echo htmlspecialchars($review['comment']); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

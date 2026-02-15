<div class="container py-4">
  <section class="surface p-4 p-lg-5 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
      <h1 class="text-primary mb-0"><?php echo htmlspecialchars($menu['title']); ?></h1>
      <span class="badge text-bg-light border">Stock : <?php echo (int)$menu['stock']; ?></span>
    </div>

    <p class="mb-3"><?php echo htmlspecialchars($menu['description']); ?></p>

    <div class="menu-meta menu-meta-large mb-3">
      <span><strong>Th&egrave;me :</strong> <?php echo htmlspecialchars($menu['theme']); ?></span>
      <span><strong>R&eacute;gime :</strong> <?php echo htmlspecialchars($menu['regime']); ?></span>
      <span><strong>Minimum :</strong> <?php echo (int)$menu['min_people']; ?> personnes</span>
      <span><strong>Prix :</strong> <?php echo number_format((float)$menu['base_price'], 2); ?> EUR</span>
    </div>

    <div class="alert alert-warning mb-0"><strong>Conditions :</strong> <?php echo htmlspecialchars($menu['conditions_text']); ?></div>
  </section>

  <?php if (!empty($images)): ?>
    <section class="surface p-4 mb-4">
      <h3 class="text-primary mb-3">Galerie</h3>
      <div class="row g-3">
        <?php foreach ($images as $image): ?>
          <div class="col-md-4">
            <img class="w-100 rounded" src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['caption']); ?>">
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="surface p-4 mb-4">
    <h3 class="text-primary mb-3">Plats du menu</h3>
    <ul class="list-group">
      <?php foreach ($dishes as $dish): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center gap-3">
          <span>
            <strong><?php echo htmlspecialchars($dish['name']); ?></strong>
            <span class="text-muted">(<?php echo htmlspecialchars($dish['type']); ?>)</span>
          </span>
          <?php if (!empty($dish['allergens'])): ?>
            <span class="badge text-bg-light border">Allerg&egrave;nes : <?php echo htmlspecialchars($dish['allergens']); ?></span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <div class="d-flex flex-wrap gap-2">
    <a class="btn btn-outline-primary" href="/menus">Retour aux menus</a>
    <?php if (auth()): ?>
      <a class="btn btn-primary" href="/orders/new?menu_id=<?php echo (int)$menu['id']; ?>">Commander ce menu</a>
    <?php else: ?>
      <a class="btn btn-primary" href="/signin">Se connecter pour commander</a>
    <?php endif; ?>
  </div>
</div>

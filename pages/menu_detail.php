<div class="container py-4">
  <h1 class="text-primary"><?php echo htmlspecialchars($menu['title']); ?></h1>
  <p><?php echo htmlspecialchars($menu['description']); ?></p>
  <p><strong>Thème:</strong> <?php echo htmlspecialchars($menu['theme']); ?> | <strong>Régime:</strong> <?php echo htmlspecialchars($menu['regime']); ?></p>
  <p><strong>Min. personnes:</strong> <?php echo (int)$menu['min_people']; ?> | <strong>Prix:</strong> <?php echo number_format((float)$menu['base_price'], 2); ?> €</p>
  <p><strong>Stock:</strong> <?php echo (int)$menu['stock']; ?></p>
  <div class="alert alert-warning"><strong>Conditions:</strong> <?php echo htmlspecialchars($menu['conditions_text']); ?></div>

  <h3 class="text-primary">Galerie</h3>
  <div class="row">
    <?php foreach ($images as $image): ?>
      <div class="col-md-4 p-2">
        <img class="w-100 rounded" src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['caption']); ?>">
      </div>
    <?php endforeach; ?>
  </div>

  <h3 class="text-primary mt-4">Plats</h3>
  <ul class="list-group">
    <?php foreach ($dishes as $dish): ?>
      <li class="list-group-item">
        <strong><?php echo htmlspecialchars($dish['name']); ?></strong> (<?php echo htmlspecialchars($dish['type']); ?>)
        <?php if (!empty($dish['allergens'])): ?>
          <span class="text-muted"> - Allergènes: <?php echo htmlspecialchars($dish['allergens']); ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <div class="mt-4">
    <?php if (auth()): ?>
      <a class="btn btn-primary" href="/orders/new?menu_id=<?php echo (int)$menu['id']; ?>">Commander</a>
    <?php else: ?>
      <a class="btn btn-outline-primary" href="/signin">Se connecter pour commander</a>
    <?php endif; ?>
  </div>
</div>

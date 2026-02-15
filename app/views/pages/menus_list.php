<div class="row g-3">
  <?php if (empty($menus)): ?>
    <div class="col-12">
      <div class="surface p-4 text-center">
        <p class="mb-0">Aucun menu trouve avec ces criteres.</p>
      </div>
    </div>
  <?php else: ?>
    <?php foreach ($menus as $menu): ?>
      <div class="col-md-6 col-xl-4">
        <article class="card h-100 menu-card">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0"><?php echo htmlspecialchars($menu['title']); ?></h5>
              <span class="badge text-bg-light border"><?php echo htmlspecialchars($menu['theme']); ?></span>
            </div>
            <p class="card-text flex-grow-1"><?php echo htmlspecialchars($menu['description']); ?></p>
            <div class="menu-meta mb-3">
              <span>Min. <?php echo (int)$menu['min_people']; ?> pers</span>
              <span><?php echo number_format((float)$menu['base_price'], 2); ?> EUR</span>
            </div>
            <a class="btn btn-outline-primary" href="/menus/<?php echo (int)$menu['id']; ?>">Voir le detail</a>
          </div>
        </article>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

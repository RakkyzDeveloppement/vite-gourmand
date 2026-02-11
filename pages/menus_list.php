<div class="row">
  <?php if (empty($menus)): ?>
    <p>Aucun menu trouvé.</p>
  <?php else: ?>
    <?php foreach ($menus as $menu): ?>
      <div class="col-md-4 p-2">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($menu['title']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($menu['description']); ?></p>
            <p class="card-text">Min. <?php echo (int)$menu['min_people']; ?> pers - <?php echo number_format((float)$menu['base_price'], 2); ?> €</p>
            <a class="btn btn-outline-primary" href="/menus/<?php echo (int)$menu['id']; ?>">Voir le détail</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

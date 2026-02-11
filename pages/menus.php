<div class="container py-4">
  <h1 class="text-primary text-center">Nos menus</h1>
  <div class="row g-3 my-3">
    <div class="col-md-3">
      <label class="form-label">Thème</label>
      <input type="text" class="form-control" id="filter-theme" value="<?php echo htmlspecialchars($filters['theme'] ?? ''); ?>" placeholder="Noèl, Pâques...">
    </div>
    <div class="col-md-3">
      <label class="form-label">Régime</label>
      <input type="text" class="form-control" id="filter-regime" value="<?php echo htmlspecialchars($filters['regime'] ?? ''); ?>" placeholder="Vegan, Végétarien...">
    </div>
    <div class="col-md-2">
      <label class="form-label">Prix max</label>
      <input type="number" class="form-control" id="filter-max" value="<?php echo htmlspecialchars($filters['max_price'] ?? ''); ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label">Prix min</label>
      <input type="number" class="form-control" id="filter-from" value="<?php echo htmlspecialchars($filters['price_from'] ?? ''); ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label">Prix max</label>
      <input type="number" class="form-control" id="filter-to" value="<?php echo htmlspecialchars($filters['price_to'] ?? ''); ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Personnes min</label>
      <input type="number" class="form-control" id="filter-people" value="<?php echo htmlspecialchars($filters['min_people'] ?? ''); ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button class="btn btn-primary w-100" id="apply-filters">Filtrer</button>
    </div>
  </div>

  <div id="menus-list">
    <?php partial('pages/menus_list', ['menus' => $menus]); ?>
  </div>
</div>

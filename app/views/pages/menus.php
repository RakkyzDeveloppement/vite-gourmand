<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Catalogue</p>
      <h1 class="text-primary mb-0">Nos menus</h1>
    </div>
    <p class="text-muted mb-0">Affinez les r&eacute;sultats avec les filtres ci-dessous.</p>
  </div>

  <section class="surface p-3 p-lg-4 mb-4">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">Th&egrave;me</label>
        <input type="text" class="form-control" id="filter-theme" value="<?php echo htmlspecialchars($filters['theme'] ?? ''); ?>" placeholder="No&euml;l, P&acirc;ques...">
      </div>
      <div class="col-md-3">
        <label class="form-label">R&eacute;gime</label>
        <input type="text" class="form-control" id="filter-regime" value="<?php echo htmlspecialchars($filters['regime'] ?? ''); ?>" placeholder="Vegan, V&eacute;g&eacute;tarien...">
      </div>
      <div class="col-md-2">
        <label class="form-label">Prix maximum</label>
        <input type="number" class="form-control" id="filter-max" value="<?php echo htmlspecialchars($filters['max_price'] ?? ''); ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Prix de</label>
        <input type="number" class="form-control" id="filter-from" value="<?php echo htmlspecialchars($filters['price_from'] ?? ''); ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Prix &agrave;</label>
        <input type="number" class="form-control" id="filter-to" value="<?php echo htmlspecialchars($filters['price_to'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Personnes minimum</label>
        <input type="number" class="form-control" id="filter-people" value="<?php echo htmlspecialchars($filters['min_people'] ?? ''); ?>">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100" id="apply-filters">Filtrer les menus</button>
      </div>
    </div>
  </section>

  <div id="menus-list">
    <?php partial('pages/menus_list', ['menus' => $menus]); ?>
  </div>
</div>

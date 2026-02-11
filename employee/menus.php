<div class="container py-4">
  <h1 class="text-primary">Gestion des menus</h1>

  <form method="post" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <div class="col-md-6">
      <label class="form-label">Titre</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Thème (ID)</label>
      <input type="number" name="theme_id" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Régime (ID)</label>
      <input type="number" name="regime_id" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Min personnes</label>
      <input type="number" name="min_people" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Prix de base</label>
      <input type="number" step="0.01" name="base_price" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Stock</label>
      <input type="number" name="stock" class="form-control" required>
    </div>
    <div class="col-12">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="col-12">
      <label class="form-label">Conditions</label>
      <textarea name="conditions_text" class="form-control" rows="3"></textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Enregistrer</button>
    </div>
  </form>

  <hr>
  <div class="list-group">
    <?php foreach ($menus as $menu): ?>
      <div class="list-group-item">
        <strong><?php echo htmlspecialchars($menu['title']); ?></strong>
        <form method="post" action="/employee/menus/<?php echo (int)$menu['id']; ?>/delete" class="d-inline">
          <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
          <button class="btn btn-sm btn-danger">Supprimer</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>

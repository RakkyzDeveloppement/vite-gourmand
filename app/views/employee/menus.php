<?php
$dayLabels = [
  1 => 'Lundi',
  2 => 'Mardi',
  3 => 'Mercredi',
  4 => 'Jeudi',
  5 => 'Vendredi',
  6 => 'Samedi',
  7 => 'Dimanche',
];
?>
<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Back-office</p>
      <h1 class="text-primary mb-0">Gestion menus, plats et horaires</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">Nouveau menu</h2>
    <form method="post" action="/employee/menus" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-6">
        <label class="form-label">Titre</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Theme</label>
        <select name="theme_id" class="form-select" required>
          <option value="">Choisir...</option>
          <?php foreach ($themes as $theme): ?>
            <option value="<?php echo (int)$theme['id']; ?>"><?php echo htmlspecialchars((string)$theme['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Regime</label>
        <select name="regime_id" class="form-select" required>
          <option value="">Choisir...</option>
          <?php foreach ($regimes as $regime): ?>
            <option value="<?php echo (int)$regime['id']; ?>"><?php echo htmlspecialchars((string)$regime['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Nb min personnes</label>
        <input type="number" name="min_people" class="form-control" required min="1">
      </div>
      <div class="col-md-4">
        <label class="form-label">Prix base</label>
        <input type="number" step="0.01" name="base_price" class="form-control" required min="0.01">
      </div>
      <div class="col-md-4">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" required min="0">
      </div>
      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="2"></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Conditions</label>
        <textarea name="conditions_text" class="form-control" rows="2"></textarea>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary">Enregistrer le menu</button>
      </div>
    </form>

    <hr class="my-4">

    <h3 class="h5 text-primary mb-3">Menus existants</h3>
    <div class="list-group">
      <?php foreach ($menus as $menu): ?>
        <div class="list-group-item d-flex justify-content-between align-items-center gap-2 flex-wrap">
          <div>
            <strong><?php echo htmlspecialchars((string)$menu['title']); ?></strong>
            <div class="small text-muted">
              <?php echo (int)$menu['min_people']; ?> pers. min - <?php echo number_format((float)$menu['base_price'], 2, ',', ' '); ?> EUR
            </div>
            <div class="small mt-1">
              <?php if ((int)($menu['is_active'] ?? 1) === 1): ?>
                <span class="badge text-bg-success">Actif</span>
              <?php else: ?>
                <span class="badge text-bg-secondary">Desactive</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="d-flex gap-2">
            <?php if ((int)($menu['is_active'] ?? 1) === 1): ?>
              <form method="post" action="/employee/menus/<?php echo (int)$menu['id']; ?>/deactivate" class="d-inline">
                <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                <button class="btn btn-sm btn-warning">Desactiver</button>
              </form>
            <?php else: ?>
              <form method="post" action="/employee/menus/<?php echo (int)$menu['id']; ?>/activate" class="d-inline">
                <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                <button class="btn btn-sm btn-success">Reactiver</button>
              </form>
            <?php endif; ?>
            <form method="post" action="/employee/menus/<?php echo (int)$menu['id']; ?>/delete" class="d-inline">
              <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
              <button class="btn btn-sm btn-danger">Supprimer</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">CRUD plats</h2>
    <form method="post" action="/employee/dishes" class="row g-3 mb-4">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-4">
        <label class="form-label">Nom du plat</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
          <option value="entree">Entree</option>
          <option value="plat">Plat</option>
          <option value="dessert">Dessert</option>
        </select>
      </div>
      <div class="col-md-5">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control">
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary">Ajouter le plat</button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Type</th>
            <th>Description</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dishes as $dish): ?>
            <tr>
              <td><?php echo (int)$dish['id']; ?></td>
              <td><?php echo htmlspecialchars((string)$dish['name']); ?></td>
              <td><?php echo htmlspecialchars((string)$dish['type']); ?></td>
              <td><?php echo htmlspecialchars((string)($dish['description'] ?? '')); ?></td>
              <td class="text-end">
                <form method="post" action="/employee/dishes/<?php echo (int)$dish['id']; ?>/delete" class="d-inline">
                  <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                  <button class="btn btn-sm btn-danger">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <section class="surface p-4 p-lg-5">
    <h2 class="h4 text-primary mb-3">Gestion horaires</h2>
    <div class="row g-3">
      <?php foreach ($schedules as $schedule): ?>
        <div class="col-12">
          <form method="post" action="/employee/schedules" class="border rounded-3 p-3">
            <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="id" value="<?php echo (int)$schedule['id']; ?>">
            <input type="hidden" name="day_of_week" value="<?php echo (int)$schedule['day_of_week']; ?>">
            <div class="row g-3 align-items-end">
              <div class="col-md-2">
                <label class="form-label mb-1">Jour</label>
                <div><strong><?php echo $dayLabels[(int)$schedule['day_of_week']] ?? 'Jour'; ?></strong></div>
              </div>
              <div class="col-md-3">
                <label class="form-label">Ouverture</label>
                <input type="time" name="open_time" class="form-control" value="<?php echo htmlspecialchars((string)substr((string)($schedule['open_time'] ?? ''), 0, 5)); ?>">
              </div>
              <div class="col-md-3">
                <label class="form-label">Fermeture</label>
                <input type="time" name="close_time" class="form-control" value="<?php echo htmlspecialchars((string)substr((string)($schedule['close_time'] ?? ''), 0, 5)); ?>">
              </div>
              <div class="col-md-2">
                <label class="form-label">Ouverture 2</label>
                <input type="time" name="open_time2" class="form-control" value="<?php echo htmlspecialchars((string)substr((string)($schedule['open_time2'] ?? ''), 0, 5)); ?>">
              </div>
              <div class="col-md-2">
                <label class="form-label">Fermeture 2</label>
                <input type="time" name="close_time2" class="form-control" value="<?php echo htmlspecialchars((string)substr((string)($schedule['close_time2'] ?? ''), 0, 5)); ?>">
              </div>
              <div class="col-md-2">
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" name="is_closed" value="1" <?php echo (int)$schedule['is_closed'] === 1 ? 'checked' : ''; ?>>
                  <label class="form-check-label">Ferme</label>
                </div>
              </div>
              <div class="col-md-10 text-md-end">
                <button class="btn btn-sm btn-primary">Enregistrer</button>
              </div>
            </div>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>

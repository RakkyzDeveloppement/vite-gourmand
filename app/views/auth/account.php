<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Espace utilisateur</p>
      <h1 class="text-primary mb-0">Mon compte</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">Informations personnelles</h2>
    <form method="post" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-6">
        <label class="form-label">Pr&eacute;nom</label>
        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">T&eacute;l&eacute;phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Adresse</label>
        <input type="text" name="address_line1" class="form-control" value="<?php echo htmlspecialchars($user['address_line1'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Code postal</label>
        <input type="text" name="postal_code" class="form-control" value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Ville</label>
        <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary" type="submit">Mettre &agrave; jour</button>
      </div>
    </form>
  </section>

  <section class="surface p-4 p-lg-5">
    <h2 class="h4 text-primary mb-3">Mes commandes</h2>
    <div class="list-group">
      <?php if (empty($orders)): ?>
        <p class="mb-0">Aucune commande.</p>
      <?php else: ?>
        <?php foreach ($orders as $order): ?>
          <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div>
                <strong>#<?php echo (int)$order['id']; ?> - <?php echo htmlspecialchars($order['menu_title']); ?></strong>
                <div>Statut : <?php echo htmlspecialchars($order['status']); ?></div>
              </div>
              <a class="btn btn-outline-primary btn-sm" href="/orders/<?php echo (int)$order['id']; ?>">D&eacute;tails</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</div>

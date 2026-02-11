<div class="container py-4">
  <h1 class="text-primary">Mon compte</h1>
  <form method="post" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <div class="col-md-6">
      <label class="form-label">Prénom</label>
      <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Nom</label>
      <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Téléphone</label>
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
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Mettre à jour</button>
    </div>
  </form>

  <hr class="my-4">
  <h2 class="text-primary">Mes commandes</h2>
  <div class="list-group">
    <?php if (empty($orders)): ?>
      <p>Aucune commande.</p>
    <?php else: ?>
      <?php foreach ($orders as $order): ?>
        <div class="list-group-item">
          <div class="d-flex justify-content-between">
            <div>
              <strong>#<?php echo (int)$order['id']; ?> - <?php echo htmlspecialchars($order['menu_title']); ?></strong>
              <div>Statut: <?php echo htmlspecialchars($order['status']); ?></div>
            </div>
            <div>
              <a class="btn btn-outline-primary btn-sm" href="/orders/<?php echo (int)$order['id']; ?>">Détails</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

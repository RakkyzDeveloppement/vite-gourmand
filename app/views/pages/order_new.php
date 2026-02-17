<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Commande</p>
      <h1 class="text-primary mb-0">Commander un menu</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5">
    <form method="post" action="/orders" class="row g-3" id="order-form">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">

      <?php if ($menu): ?>
        <input type="hidden" name="menu_id" id="menu-id" value="<?php echo (int)$menu['id']; ?>" data-base-price="<?php echo (float)$menu['base_price']; ?>" data-min-people="<?php echo (int)$menu['min_people']; ?>">
        <div class="col-12">
          <div class="alert alert-secondary mb-0"><strong>Menu choisi :</strong> <?php echo htmlspecialchars($menu['title']); ?> (minimum <?php echo (int)$menu['min_people']; ?> personnes)</div>
        </div>
      <?php else: ?>
        <div class="col-md-6">
          <label class="form-label">Menu</label>
          <select name="menu_id" id="menu-id" class="form-select" required>
            <option value="">Choisir un menu</option>
            <?php foreach ($menus as $m): ?>
              <option value="<?php echo (int)$m['id']; ?>" data-base-price="<?php echo (float)$m['base_price']; ?>" data-min-people="<?php echo (int)$m['min_people']; ?>">
                <?php echo htmlspecialchars($m['title']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?>

      <div class="col-md-6">
        <label class="form-label">Nombre de personnes</label>
        <input type="number" name="people_count" id="people-count" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Adresse de livraison</label>
        <input type="text" name="delivery_address" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Code postal</label>
        <input type="text" name="delivery_postal" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Ville</label>
        <input type="text" name="delivery_city" id="delivery-city" class="form-control" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="date" name="delivery_date" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Heure</label>
        <input type="time" name="delivery_time" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Lieu de prestation</label>
        <input type="text" name="delivery_place" class="form-control" required>
      </div>

      <div class="col-md-3">
        <label class="form-label">Distance (km)</label>
        <input type="number" step="0.1" name="distance_km" id="distance-km" class="form-control" value="0">
      </div>

      <div class="col-12">
        <div class="alert alert-info mb-0" id="price-summary">
          <div class="price-line"><span>Prix menu :</span> <strong><span id="menu-price">0.00</span> EUR</strong></div>
          <div class="price-line"><span>Livraison :</span> <strong><span id="delivery-fee">0.00</span> EUR</strong></div>
          <div class="price-line price-total"><span>Total :</span> <strong><span id="total-price">0.00</span> EUR</strong></div>
        </div>
      </div>

      <div class="col-12 d-flex justify-content-end order-submit-row">
        <button class="btn btn-primary" type="submit">Valider la commande</button>
      </div>
    </form>
  </section>
</div>


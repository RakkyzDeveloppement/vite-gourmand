<div class="container py-4">
  <section class="surface p-4 p-lg-5 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <h1 class="text-primary mb-0">Commande #<?php echo (int)$order['id']; ?></h1>
      <span class="badge text-bg-light border"><?php echo htmlspecialchars((string)$order['status']); ?></span>
    </div>

    <div class="menu-meta menu-meta-large">
      <span><strong>Menu :</strong> <?php echo htmlspecialchars((string)$order['menu_title']); ?></span>
      <span><strong>Total :</strong> <?php echo number_format((float)$order['total_price'], 2); ?> EUR</span>
    </div>
  </section>

  <?php if ($order['status'] === 'recue'): ?>
    <section class="surface p-4 p-lg-5 mb-4">
      <h3 class="text-primary mb-3">Modifier ma commande (hors menu)</h3>
      <form method="post" action="/orders/<?php echo (int)$order['id']; ?>/update" class="row g-3">
        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">

        <div class="col-md-6">
          <label class="form-label">Menu (non modifiable)</label>
          <input type="text" class="form-control" value="<?php echo htmlspecialchars((string)$order['menu_title']); ?>" disabled>
        </div>

        <div class="col-md-6">
          <label class="form-label">Nombre de personnes</label>
          <input type="number" name="people_count" class="form-control" required min="1" value="<?php echo (int)$order['people_count']; ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Adresse de livraison</label>
          <input type="text" name="delivery_address" class="form-control" required value="<?php echo htmlspecialchars((string)$order['delivery_address']); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Code postal</label>
          <input type="text" name="delivery_postal" class="form-control" required value="<?php echo htmlspecialchars((string)$order['delivery_postal']); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Ville</label>
          <input type="text" name="delivery_city" class="form-control" required value="<?php echo htmlspecialchars((string)$order['delivery_city']); ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">Date</label>
          <input type="date" name="delivery_date" class="form-control" required value="<?php echo htmlspecialchars((string)$order['delivery_date']); ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Heure</label>
          <input type="time" name="delivery_time" class="form-control" required value="<?php echo htmlspecialchars((string)substr((string)$order['delivery_time'], 0, 5)); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Lieu de prestation</label>
          <input type="text" name="delivery_place" class="form-control" required value="<?php echo htmlspecialchars((string)$order['delivery_place']); ?>">
        </div>

        <div class="col-md-3">
          <label class="form-label">Distance (km)</label>
          <input type="number" step="0.1" name="distance_km" class="form-control" value="<?php echo htmlspecialchars((string)$order['distance_km']); ?>">
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary" type="submit">Enregistrer les modifications</button>
        </div>
      </form>
    </section>
  <?php else: ?>
    <section class="surface p-4 p-lg-5 mb-4">
      <div class="alert alert-warning mb-0">
        Modification indisponible : cette commande a déjà été acceptée ou traitée par l'equipe.
      </div>
    </section>
  <?php endif; ?>

  <section class="surface p-4 p-lg-5 mb-4">
    <h3 class="text-primary mb-3">Suivi de commande</h3>
    <ul class="list-group">
      <?php foreach ($history as $h): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><?php echo htmlspecialchars((string)$h['status']); ?></span>
          <small class="text-muted"><?php echo htmlspecialchars((string)$h['created_at']); ?></small>
        </li>
      <?php endforeach; ?>
    </ul>

    <?php if ($order['status'] === 'recue'): ?>
      <form method="post" action="/orders/<?php echo (int)$order['id']; ?>/cancel" class="mt-3 d-flex justify-content-end">
        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
        <button class="btn btn-danger" type="submit">Annuler la commande</button>
      </form>
    <?php endif; ?>
  </section>

  <?php if ($order['status'] === 'terminee'): ?>
    <section class="surface p-4 p-lg-5">
      <h3 class="text-primary mb-3">Donner un avis</h3>
      <form method="post" action="/orders/<?php echo (int)$order['id']; ?>/review" class="row g-3">
        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
        <div class="col-md-2">
          <label class="form-label">Note</label>
          <input type="number" name="rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Commentaire</label>
          <textarea name="comment" class="form-control" rows="4" required></textarea>
        </div>
        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary" type="submit">Envoyer</button>
        </div>
      </form>
    </section>
  <?php endif; ?>
</div>

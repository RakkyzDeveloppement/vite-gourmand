<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Back-office</p>
      <h1 class="text-primary mb-0">Espace employ&eacute;</h1>
      <a href="/employee/menus" class="btn btn-sm btn-outline-primary mt-2">Gerer menus, plats, horaires</a>
    </div>
  </div>

  <section class="surface p-4 p-lg-5 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
      <h2 class="h4 text-primary mb-0">Commandes</h2>
      <form method="get" class="d-flex gap-2 flex-wrap">
        <select name="status" class="form-select">
          <option value="" <?php echo ($status ?? '') === '' ? 'selected' : ''; ?>>Tous les statuts</option>
          <option value="recue" <?php echo ($status ?? '') === 'recue' ? 'selected' : ''; ?>>re&ccedil;ue</option>
          <option value="acceptee" <?php echo ($status ?? '') === 'acceptee' ? 'selected' : ''; ?>>accept&eacute;e</option>
          <option value="en_preparation" <?php echo ($status ?? '') === 'en_preparation' ? 'selected' : ''; ?>>en pr&eacute;paration</option>
          <option value="en_livraison" <?php echo ($status ?? '') === 'en_livraison' ? 'selected' : ''; ?>>en cours de livraison</option>
          <option value="livree" <?php echo ($status ?? '') === 'livree' ? 'selected' : ''; ?>>livr&eacute;e</option>
          <option value="en_attente_retour_materiel" <?php echo ($status ?? '') === 'en_attente_retour_materiel' ? 'selected' : ''; ?>>en attente retour mat&eacute;riel</option>
          <option value="terminee" <?php echo ($status ?? '') === 'terminee' ? 'selected' : ''; ?>>termin&eacute;e</option>
        </select>
        <input type="text" name="client_email" class="form-control" placeholder="Filtrer par email client" value="<?php echo htmlspecialchars($clientEmail ?? ''); ?>">
        <button class="btn btn-primary">Filtrer</button>
      </form>
    </div>

    <div class="list-group">
      <?php foreach ($orders as $order): ?>
        <div class="list-group-item">
          <div class="d-flex justify-content-between flex-wrap gap-3">
            <div>
              <strong>#<?php echo (int)$order['id']; ?> - <?php echo htmlspecialchars($order['menu_title']); ?></strong>
              <div>Client : <?php echo htmlspecialchars($order['user_email']); ?></div>
              <div>Statut : <?php echo htmlspecialchars($order['status']); ?></div>
            </div>
            <div class="d-grid gap-2" style="min-width: 320px;">
              <form method="post" action="/employee/orders/<?php echo (int)$order['id']; ?>/status" class="d-flex gap-2">
                <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                <select name="status" class="form-select form-select-sm">
                  <option value="acceptee">accept&eacute;e</option>
                  <option value="en_preparation">en pr&eacute;paration</option>
                  <option value="en_livraison">en cours de livraison</option>
                  <option value="livree">livr&eacute;e</option>
                  <option value="en_attente_retour_materiel">en attente retour mat&eacute;riel</option>
                  <option value="terminee">termin&eacute;e</option>
                </select>
                <button class="btn btn-sm btn-primary">Mettre &agrave; jour</button>
              </form>
              <form method="post" action="/employee/orders/<?php echo (int)$order['id']; ?>/cancel" class="d-flex gap-2">
                <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                <input type="text" name="reason" class="form-control form-control-sm" placeholder="Motif d'annulation" required>
                <input type="text" name="contact_mode" class="form-control form-control-sm" placeholder="Contact (GSM/email)" required>
                <button class="btn btn-sm btn-danger">Annuler</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="surface p-4 p-lg-5">
    <h2 class="h4 text-primary mb-3">Avis en attente</h2>
    <div class="list-group">
      <?php foreach ($reviews as $review): ?>
        <div class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-2">
          <div>
            <strong><?php echo htmlspecialchars($review['email']); ?></strong> - Note <?php echo (int)$review['rating']; ?>/5
            <p class="mb-0"><?php echo htmlspecialchars($review['comment']); ?></p>
          </div>
          <div class="d-flex gap-2">
            <form method="post" action="/employee/reviews/<?php echo (int)$review['id']; ?>/validate">
              <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
              <button class="btn btn-sm btn-success">Valider</button>
            </form>
            <form method="post" action="/employee/reviews/<?php echo (int)$review['id']; ?>/reject">
              <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
              <button class="btn btn-sm btn-danger">Refuser</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>

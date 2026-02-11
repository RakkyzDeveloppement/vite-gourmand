<div class="container py-4">
  <h1 class="text-primary">Espace employé</h1>

  <h2 class="text-primary mt-4">Commandes</h2>
  <form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
      <select name="status" class="form-select">
        <option value="">Tous les statuts</option>
        <option value="recue">reçue</option>
        <option value="acceptee">acceptée</option>
        <option value="en_preparation">en préparation</option>
        <option value="en_livraison">en cours de livraison</option>
        <option value="livree">livrée</option>
        <option value="en_attente_retour_materiel">en attente retour matériel</option>
        <option value="terminee">terminée</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Filtrer</button>
    </div>
  </form>

  <div class="list-group">
    <?php foreach ($orders as $order): ?>
      <div class="list-group-item">
        <div class="d-flex justify-content-between">
          <div>
            <strong>#<?php echo (int)$order['id']; ?> - <?php echo htmlspecialchars($order['menu_title']); ?></strong>
            <div>Client: <?php echo htmlspecialchars($order['user_email']); ?></div>
            <div>Statut: <?php echo htmlspecialchars($order['status']); ?></div>
          </div>
          <div>
            <form method="post" action="/employee/orders/<?php echo (int)$order['id']; ?>/status" class="d-flex gap-2">
              <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
              <select name="status" class="form-select form-select-sm">
                <option value="acceptee">acceptée</option>
                <option value="en_preparation">en préparation</option>
                <option value="en_livraison">en cours de livraison</option>
                <option value="livree">livrée</option>
                <option value="en_attente_retour_materiel">en attente retour matériel</option>
                <option value="terminee">terminée</option>
              </select>
              <button class="btn btn-sm btn-primary">Mettre à jour</button>
            </form>
            <form method="post" action="/employee/orders/<?php echo (int)$order['id']; ?>/cancel" class="mt-2">
              <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
              <input type="text" name="reason" class="form-control form-control-sm mb-1" placeholder="Motif d'annulation">
              <input type="text" name="contact_mode" class="form-control form-control-sm mb-1" placeholder="Contact (GSM/email)">
              <button class="btn btn-sm btn-danger">Annuler</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <h2 class="text-primary mt-5">Avis en attente</h2>
  <div class="list-group">
    <?php foreach ($reviews as $review): ?>
      <div class="list-group-item">
        <strong><?php echo htmlspecialchars($review['email']); ?></strong> - Note <?php echo (int)$review['rating']; ?>/5
        <p><?php echo htmlspecialchars($review['comment']); ?></p>
        <form method="post" action="/employee/reviews/<?php echo (int)$review['id']; ?>/validate" class="d-inline">
          <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
          <button class="btn btn-sm btn-success">Valider</button>
        </form>
        <form method="post" action="/employee/reviews/<?php echo (int)$review['id']; ?>/reject" class="d-inline">
          <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
          <button class="btn btn-sm btn-danger">Refuser</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>

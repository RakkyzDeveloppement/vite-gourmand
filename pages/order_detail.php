<div class="container py-4">
  <h1 class="text-primary">Commande #<?php echo (int)$order['id']; ?></h1>
  <p><strong>Menu:</strong> <?php echo htmlspecialchars($order['menu_title']); ?></p>
  <p><strong>Statut:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
  <p><strong>Total:</strong> <?php echo number_format((float)$order['total_price'], 2); ?> €</p>

  <h3 class="text-primary">Suivi</h3>
  <ul class="list-group">
    <?php foreach ($history as $h): ?>
      <li class="list-group-item"><?php echo htmlspecialchars($h['status']); ?> - <?php echo htmlspecialchars($h['created_at']); ?></li>
    <?php endforeach; ?>
  </ul>

  <?php if ($order['status'] === 'recue'): ?>
    <form method="post" action="/orders/<?php echo (int)$order['id']; ?>/cancel" class="mt-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <button class="btn btn-danger" type="submit">Annuler la commande</button>
    </form>
  <?php endif; ?>

  <?php if ($order['status'] === 'terminee'): ?>
    <hr>
    <h3 class="text-primary">Donner un avis</h3>
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
      <div class="col-12">
        <button class="btn btn-primary" type="submit">Envoyer</button>
      </div>
    </form>
  <?php endif; ?>
</div>

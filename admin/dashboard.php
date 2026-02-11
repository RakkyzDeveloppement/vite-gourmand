<div class="container py-4">
  <h1 class="text-primary">Espace administrateur</h1>

  <h2 class="text-primary mt-4">Créer un compte employé</h2>
  <form method="post" action="/admin/employees" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Mot de passe temporaire</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Créer</button>
    </div>
  </form>

  <h2 class="text-primary mt-5">Statistiques commandes (NoSQL)</h2>
  <?php if (empty($stats)): ?>
    <p>MongoDB non configuré ou aucune donnée.</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>Menu</th>
          <th>Commandes</th>
          <th>Chiffre d'affaires</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($stats as $stat): ?>
          <tr>
            <td><?php echo htmlspecialchars($stat['menu_title'] ?? ''); ?></td>
            <td><?php echo (int)($stat['orders_count'] ?? 0); ?></td>
            <td><?php echo number_format((float)($stat['revenue'] ?? 0), 2); ?> �</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<div class="container py-4">
  <h1 class="text-primary">Réinitialiser le mot de passe</h1>
  <form method="post" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <div class="col-md-6">
      <label class="form-label">Nouveau mot de passe</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Mettre à jour</button>
    </div>
  </form>
</div>

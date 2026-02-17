<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">S&eacute;curit&eacute;</p>
      <h1 class="text-primary mb-0">R&eacute;initialiser le mot de passe</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5">
    <form method="post" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="col-md-6">
        <label class="form-label">Nouveau mot de passe</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary" type="submit">Mettre &agrave; jour</button>
      </div>
    </form>
  </section>
</div>

<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Acc&egrave;s</p>
      <h1 class="text-primary mb-0">Connexion</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5">
    <form method="post" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="col-12 d-flex flex-wrap gap-2 justify-content-between align-items-center">
        <a class="btn btn-link p-0" href="/forgot">Mot de passe oubli&eacute; ?</a>
        <button class="btn btn-primary" type="submit">Se connecter</button>
      </div>
    </form>
  </section>
</div>

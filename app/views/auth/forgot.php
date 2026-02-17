<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">S&eacute;curit&eacute;</p>
      <h1 class="text-primary mb-0">Mot de passe oubli&eacute;</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5">
    <form method="post" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary" type="submit">Envoyer le lien</button>
      </div>
    </form>
  </section>
</div>

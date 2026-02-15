<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Nouveau compte</p>
      <h1 class="text-primary mb-0">Inscription</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5">
    <form method="post" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-6">
        <label class="form-label">Pr&eacute;nom</label>
        <input type="text" name="first_name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input type="text" name="last_name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">T&eacute;l&eacute;phone</label>
        <input type="text" name="phone" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Adresse</label>
        <input type="text" name="address_line1" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Code postal</label>
        <input type="text" name="postal_code" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Ville</label>
        <input type="text" name="city" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
        <small class="text-muted">10 caract&egrave;res min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caract&egrave;re sp&eacute;cial.</small>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary" type="submit">Cr&eacute;er mon compte</button>
      </div>
    </form>
  </section>
</div>

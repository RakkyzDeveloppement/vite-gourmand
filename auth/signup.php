<div class="container py-4">
  <h1 class="text-primary">Inscription</h1>
  <form method="post" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <div class="col-md-6">
      <label class="form-label">Prénom</label>
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
      <label class="form-label">Téléphone</label>
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
      <small class="text-muted">10 caractères min, 1 maj, 1 min, 1 chiffre, 1 spécial</small>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Créer mon compte</button>
    </div>
  </form>
</div>

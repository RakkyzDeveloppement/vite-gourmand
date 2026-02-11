<div class="container py-4">
  <h1 class="text-primary">Connexion</h1>
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
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Se connecter</button>
      <a class="btn btn-link" href="/forgot">Mot de passe oublié</a>
    </div>
  </form>
</div>

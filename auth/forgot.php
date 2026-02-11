<div class="container py-4">
  <h1 class="text-primary">Mot de passe oublié</h1>
  <form method="post" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Envoyer le lien</button>
    </div>
  </form>
</div>

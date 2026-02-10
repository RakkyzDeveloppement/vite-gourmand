<div class="container py-4">
  <h1 class="text-primary">Contact</h1>
  <form method="post" class="row g-3">
    <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
    <div class="col-md-6">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Titre</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="col-12">
      <label class="form-label">Message</label>
      <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Envoyer</button>
    </div>
  </form>
</div>

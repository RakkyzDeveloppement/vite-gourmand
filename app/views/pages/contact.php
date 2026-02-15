<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">&Eacute;change</p>
      <h1 class="text-primary mb-0">Contact</h1>
    </div>
    <p class="text-muted mb-0">Une question sur un menu ou une commande ? &Eacute;crivez-nous.</p>
  </div>

  <section class="surface p-4 p-lg-5">
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
        <textarea name="message" class="form-control" rows="6" required></textarea>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary px-4" type="submit">Envoyer</button>
      </div>
    </form>
  </section>
</div>

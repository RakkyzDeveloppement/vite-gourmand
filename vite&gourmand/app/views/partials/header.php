<?php $user = auth(); ?>
<header>
  <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">Vite & Gourmand</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="/">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="/menus">Menus</a></li>
          <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
          <?php if ($user): ?>
            <li class="nav-item"><a class="nav-link" href="/account">Mon compte</a></li>
            <?php if ($user['role'] === 'employe' || $user['role'] === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="/employee">Espace employé</a></li>
            <?php endif; ?>
            <?php if ($user['role'] === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="/admin">Admin</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="/signout">Déconnexion</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/signin">Connexion</a></li>
            <li class="nav-item"><a class="nav-link" href="/signup">Inscription</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>

<?php
$chartLabels = [];
$chartOrders = [];
$chartRevenue = [];

foreach (($stats ?? []) as $stat) {
    $chartLabels[] = (string)($stat['menu_title'] ?? 'Menu');
    $chartOrders[] = (int)($stat['orders_count'] ?? 0);
    $chartRevenue[] = (float)($stat['revenue'] ?? 0);
}
?>
<div class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
    <div>
      <p class="eyebrow mb-1">Administration</p>
      <h1 class="text-primary mb-0">Espace administrateur</h1>
    </div>
  </div>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">Creer un compte employe</h2>
    <form method="post" action="/admin/employees" class="row g-3">
      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Mot de passe temporaire</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary">Creer</button>
      </div>
    </form>
  </section>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">Comptes employes</h2>
    <?php if (empty($employees)): ?>
      <p class="mb-0">Aucun compte employe.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0 align-middle">
          <thead>
            <tr>
              <th>Email</th>
              <th>Nom</th>
              <th>Statut</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($employees as $employee): ?>
              <tr>
                <td><?php echo htmlspecialchars((string)$employee['email']); ?></td>
                <td><?php echo htmlspecialchars(trim(((string)($employee['first_name'] ?? '')) . ' ' . ((string)($employee['last_name'] ?? '')))); ?></td>
                <td>
                  <?php if ((int)$employee['is_active'] === 1): ?>
                    <span class="badge text-bg-success">Actif</span>
                  <?php else: ?>
                    <span class="badge text-bg-secondary">Desactive</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <?php if ((int)$employee['is_active'] === 1): ?>
                    <form method="post" action="/admin/employees/<?php echo (int)$employee['id']; ?>/disable" class="d-inline">
                      <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
                      <button class="btn btn-sm btn-danger">Desactiver</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">Chiffre d'affaires par menu</h2>

    <form method="get" class="row g-3 mb-3">
      <div class="col-md-4">
        <label class="form-label">Menu</label>
        <select name="ca_menu_id" class="form-select">
          <option value="0">Tous les menus</option>
          <?php foreach (($menus ?? []) as $menu): ?>
            <option value="<?php echo (int)$menu['id']; ?>" <?php echo ((int)($selectedMenuId ?? 0) === (int)$menu['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars((string)$menu['title']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Du</label>
        <input type="date" name="ca_from" class="form-control" value="<?php echo htmlspecialchars((string)($dateFrom ?? '')); ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Au</label>
        <input type="date" name="ca_to" class="form-control" value="<?php echo htmlspecialchars((string)($dateTo ?? '')); ?>">
      </div>
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button class="btn btn-primary w-100">Filtrer</button>
      </div>
    </form>

    <div class="alert alert-info d-flex justify-content-between flex-wrap gap-2">
      <strong>CA total filtre :</strong>
      <span><?php echo number_format((float)($caTotal ?? 0), 2, ',', ' '); ?> EUR</span>
    </div>

    <?php if (empty($caRows)): ?>
      <p class="mb-0">Aucune commande sur cette periode.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Menu</th>
              <th>Commandes</th>
              <th>CA</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($caRows as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars((string)($row['menu_title'] ?? '')); ?></td>
                <td><?php echo (int)($row['orders_count'] ?? 0); ?></td>
                <td><?php echo number_format((float)($row['revenue'] ?? 0), 2, ',', ' '); ?> EUR</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>

  <section class="surface p-4 p-lg-5 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h2 class="h4 text-primary mb-0">Synchronisation NoSQL</h2>
      <form method="post" action="/admin/nosql/sync" class="m-0">
        <input type="hidden" name="_csrf" value="<?php echo csrf_token(); ?>">
        <button class="btn btn-outline-primary">Synchroniser depuis SQL</button>
      </form>
    </div>
    <p class="text-muted mb-0 mt-2">Utilise les commandes SQL existantes pour reconstruire les stats MongoDB.</p>
  </section>

  <section class="surface p-4 p-lg-5 mb-4">
    <h2 class="h4 text-primary mb-3">Comparaison commandes / chiffre d'affaires</h2>
    <?php if (empty($stats)): ?>
      <p class="mb-0">MongoDB non configure ou aucune donnee.</p>
    <?php else: ?>
      <div style="height: 320px;">
        <canvas id="menuStatsChart"></canvas>
      </div>
    <?php endif; ?>
  </section>

  <section class="surface p-4 p-lg-5">
    <h2 class="h4 text-primary mb-3">Statistiques des commandes</h2>
    <?php if (empty($stats)): ?>
      <p class="mb-0">MongoDB non configure ou aucune donnee.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Menu</th>
              <th>Commandes</th>
              <th>Chiffre d'affaires</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($stats as $stat): ?>
              <tr>
                <td><?php echo htmlspecialchars((string)($stat['menu_title'] ?? '')); ?></td>
                <td><?php echo (int)($stat['orders_count'] ?? 0); ?></td>
                <td><?php echo number_format((float)($stat['revenue'] ?? 0), 2, ',', ' '); ?> EUR</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</div>

<?php if (!empty($stats)): ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    (function () {
      const canvas = document.getElementById('menuStatsChart');
      if (!canvas) return;

      const labels = <?php echo json_encode($chartLabels, JSON_UNESCAPED_UNICODE); ?>;
      const orders = <?php echo json_encode($chartOrders); ?>;
      const revenue = <?php echo json_encode($chartRevenue); ?>;

      new Chart(canvas, {
        type: 'bar',
        data: {
          labels,
          datasets: [
            {
              label: 'Commandes',
              data: orders,
              backgroundColor: 'rgba(194, 108, 45, 0.7)',
              borderColor: 'rgba(194, 108, 45, 1)',
              borderWidth: 1,
              yAxisID: 'yOrders'
            },
            {
              label: 'CA (EUR)',
              data: revenue,
              type: 'line',
              borderColor: 'rgba(31, 42, 38, 1)',
              backgroundColor: 'rgba(31, 42, 38, 0.15)',
              tension: 0.3,
              yAxisID: 'yRevenue'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yOrders: {
              type: 'linear',
              position: 'left',
              beginAtZero: true,
              title: { display: true, text: 'Commandes' }
            },
            yRevenue: {
              type: 'linear',
              position: 'right',
              beginAtZero: true,
              title: { display: true, text: 'EUR' },
              grid: { drawOnChartArea: false }
            }
          }
        }
      });
    })();
  </script>
<?php endif; ?>


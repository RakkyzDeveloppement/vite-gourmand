document.addEventListener('DOMContentLoaded', () => {
  const applyBtn = document.getElementById('apply-filters');
  if (applyBtn) {
    applyBtn.addEventListener('click', async () => {
      const params = new URLSearchParams({
        ajax: '1',
        theme: document.getElementById('filter-theme').value.trim(),
        regime: document.getElementById('filter-regime').value.trim(),
        max_price: document.getElementById('filter-max').value.trim(),
        price_from: document.getElementById('filter-from').value.trim(),
        price_to: document.getElementById('filter-to').value.trim(),
        min_people: document.getElementById('filter-people').value.trim(),
      });

      const res = await fetch(`/menus?${params.toString()}`);
      const html = await res.text();
      document.getElementById('menus-list').innerHTML = html;
    });
  }

  const menuEl = document.getElementById('menu-id');
  const peopleEl = document.getElementById('people-count');
  const cityEl = document.getElementById('delivery-city');
  const distanceEl = document.getElementById('distance-km');

  const menuPriceEl = document.getElementById('menu-price');
  const deliveryFeeEl = document.getElementById('delivery-fee');
  const totalPriceEl = document.getElementById('total-price');

  function calcPrice() {
    if (!menuEl || !peopleEl || !cityEl || !distanceEl) {
      return;
    }

    let basePrice = 0;
    let minPeople = 0;

    if (menuEl.tagName === 'SELECT') {
      const selected = menuEl.options[menuEl.selectedIndex];
      basePrice = parseFloat(selected?.dataset.basePrice || '0');
      minPeople = parseInt(selected?.dataset.minPeople || '0', 10);
    } else {
      basePrice = parseFloat(menuEl.dataset.basePrice || '0');
      minPeople = parseInt(menuEl.dataset.minPeople || '0', 10);
    }

    const people = parseInt(peopleEl.value || '0', 10);
    let menuPrice = basePrice;
    if (people >= minPeople + 5) {
      menuPrice = menuPrice * 0.9;
    }

    const city = cityEl.value.trim().toLowerCase();
    const distance = parseFloat(distanceEl.value || '0');
    let delivery = 0;
    if (city && city !== 'bordeaux') {
      delivery = 5 + (0.59 * distance);
    }

    const total = menuPrice + delivery;

    if (menuPriceEl && deliveryFeeEl && totalPriceEl) {
      menuPriceEl.textContent = menuPrice.toFixed(2);
      deliveryFeeEl.textContent = delivery.toFixed(2);
      totalPriceEl.textContent = total.toFixed(2);
    }
  }

  if (menuEl) {
    menuEl.addEventListener('change', calcPrice);
  }
  if (peopleEl) {
    peopleEl.addEventListener('input', calcPrice);
  }
  if (cityEl) {
    cityEl.addEventListener('input', calcPrice);
  }
  if (distanceEl) {
    distanceEl.addEventListener('input', calcPrice);
  }

  calcPrice();
});

const CART_KEY = 'atelier-noma-cart';

const readCart = () => {
  try { return JSON.parse(localStorage.getItem(CART_KEY)) || []; } catch { return []; }
};
const writeCart = (cart) => localStorage.setItem(CART_KEY, JSON.stringify(cart));
const money = (value) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(value);
const PRODUCTS_KEY = 'atelier-noma-products';
const USER_KEY = 'atelier-noma-user';

function updateCartCount() {
  const count = readCart().reduce((total, item) => total + item.quantity, 0);
  document.querySelectorAll('[data-cart-count]').forEach((node) => { node.textContent = count; });
}

function addToCart(item) {
  const cart = readCart();
  const existing = cart.find((entry) => entry.id === item.id);
  if (existing) existing.quantity += 1;
  else cart.push({ ...item, quantity: 1 });
  writeCart(cart);
  updateCartCount();
  const button = document.querySelector(`[data-add-to-cart="${item.id}"]`);
  if (button) { button.textContent = 'Added to collection'; setTimeout(() => { button.textContent = 'Add to collection'; }, 1600); }
}

function renderCart() {
  const container = document.querySelector('[data-cart-items]');
  if (!container) return;
  const cart = readCart();
  const empty = document.querySelector('[data-empty-cart]');
  const summary = document.querySelector('[data-summary]');
  if (!cart.length) { container.innerHTML = ''; empty.hidden = false; summary.hidden = true; return; }
  empty.hidden = true; summary.hidden = false;
  container.innerHTML = cart.map((item) => `<article class="cart-item"><img src="${item.image}" alt="${item.name}"><div><h3>${item.name}</h3><p>${item.material} · Qty ${item.quantity}</p></div><div><p>${money(item.price * item.quantity)}</p><button class="remove-item" data-remove="${item.id}">Remove</button></div></article>`).join('');
  const subtotal = cart.reduce((total, item) => total + item.price * item.quantity, 0);
  document.querySelector('[data-subtotal]').textContent = money(subtotal);
  document.querySelector('[data-total]').textContent = money(subtotal);
  container.querySelectorAll('[data-remove]').forEach((button) => button.addEventListener('click', () => { writeCart(readCart().filter((item) => item.id !== button.dataset.remove)); updateCartCount(); renderCart(); }));
}

function initCatalogTools() {
  const catalog = document.querySelector('.catalog-list');
  if (!catalog) return;
  const tools = document.createElement('section');
  tools.className = 'catalog-tools reveal is-visible';
  tools.innerHTML = '<label class="search-field" for="catalog-search">Search the collection<input id="catalog-search" type="search" placeholder="Search by name or material"></label><div class="category-filters" aria-label="Filter by category"><button class="filter-button active" data-category="all">All pieces</button><button class="filter-button" data-category="Seating">Seating</button><button class="filter-button" data-category="Occasional">Occasional</button><button class="filter-button" data-category="Storage">Storage</button></div>';
  catalog.parentNode.insertBefore(tools, catalog);
  const products = [...catalog.querySelectorAll('.product')];
  products.forEach((product) => {
    const category = product.querySelector('.eyebrow')?.textContent || '';
    product.dataset.category = category.includes('Occasional') ? 'Occasional' : category.includes('Storage') ? 'Storage' : 'Seating';
    product.dataset.search = product.textContent.toLowerCase();
  });
  let category = 'all';
  const applyFilters = () => { const term = tools.querySelector('input').value.toLowerCase(); products.forEach((product) => product.classList.toggle('is-filtered', (category !== 'all' && product.dataset.category !== category) || !product.dataset.search.includes(term))); };
  tools.querySelector('input').addEventListener('input', applyFilters);
  tools.querySelectorAll('[data-category]').forEach((button) => button.addEventListener('click', () => { category = button.dataset.category; tools.querySelectorAll('.filter-button').forEach((item) => item.classList.toggle('active', item === button)); applyFilters(); }));
}

function initAdminTools() {
  const dashboard = document.querySelector('.dashboard');
  if (!dashboard) return;
  document.title = 'Admin — Atelier Noma';
  document.querySelectorAll('.site-nav a, .admin-side nav a').forEach((link) => { if (link.textContent.trim() === 'Archive') link.textContent = 'Admin'; });
  const upload = document.createElement('section');
  upload.className = 'admin-upload reveal is-visible';
  upload.innerHTML = '<p class="eyebrow">Admin / Product management</p><h2>Upload a new piece.</h2><form class="form-grid" data-product-form><div class="field"><label for="product-name">Product name</label><input id="product-name" required></div><div class="field"><label for="product-category">Category</label><select id="product-category"><option>Seating</option><option>Occasional</option><option>Storage</option><option>Lighting</option></select></div><div class="field"><label for="product-price">Price</label><input id="product-price" type="number" min="0" required></div><div class="field"><label for="product-stock">Stock</label><input id="product-stock" type="number" min="0" required></div><div class="field"><label for="product-image">Image URL</label><input id="product-image" type="url" required></div><button class="button" type="submit">Publish product</button></form>';
  dashboard.insertBefore(upload, dashboard.querySelector('.stats'));
  upload.querySelector('form').addEventListener('submit', (event) => { event.preventDefault(); const form = event.currentTarget; const product = { name: form.elements['product-name'].value, category: form.elements['product-category'].value, price: form.elements['product-price'].value, stock: form.elements['product-stock'].value, image: form.elements['product-image'].value, createdAt: new Date().toISOString() }; const products = JSON.parse(localStorage.getItem(PRODUCTS_KEY) || '[]'); products.push(product); localStorage.setItem(PRODUCTS_KEY, JSON.stringify(products)); form.reset(); const button = form.querySelector('button'); button.textContent = 'Published'; setTimeout(() => { button.textContent = 'Publish product'; }, 1600); });
}

function initSiteShell() {
  const header = document.querySelector('.site-header');
  if (!header) return;
  const profile = document.createElement('a');
  profile.className = 'header-profile'; profile.href = 'profile.html'; profile.textContent = localStorage.getItem(USER_KEY) ? 'Profile' : 'Sign in';
  header.insertBefore(profile, header.querySelector('.header-cart'));
  const footer = document.createElement('footer');
  footer.className = 'site-footer';
  footer.innerHTML = '<div><p class="eyebrow">A slower way to live</p><h2>Stay<br>in the know.</h2></div><div class="footer-copy"><p>Notes on material, craft, and rooms with a point of view. A quiet letter from the Atelier, sent occasionally.</p><form class="newsletter"><input type="email" placeholder="Your email address" aria-label="Your email address" required><button type="submit" aria-label="Subscribe">↗</button></form></div><div class="footer-meta"><span>Milano / Paris / New York</span><span>© Atelier Noma 2026</span><span><a href="contact.html">Contact</a> &nbsp; <a href="profile.html">Profile</a></span></div>';
  document.body.appendChild(footer);
  footer.querySelector('form').addEventListener('submit', (event) => { event.preventDefault(); const button = event.currentTarget.querySelector('button'); button.textContent = '✓'; });
}

function initProfile() {
  const panel = document.querySelector('[data-auth-panel]');
  if (!panel) return;
  panel.querySelectorAll('input').forEach((input) => { input.name = input.id; });
  const account = document.querySelector('[data-profile-account]');
  const storedUser = JSON.parse(localStorage.getItem(USER_KEY) || 'null');
  const showAccount = (user) => { panel.hidden = true; account.hidden = false; document.querySelector('[data-profile-name]').textContent = user.name; };
  if (storedUser) showAccount(storedUser);
  panel.querySelectorAll('[data-auth-tab]').forEach((tab) => tab.addEventListener('click', () => { panel.querySelectorAll('[data-auth-tab]').forEach((item) => item.classList.toggle('active', item === tab)); panel.querySelectorAll('[data-auth-form]').forEach((form) => { form.hidden = form.dataset.authForm !== tab.dataset.authTab; }); }));
  panel.querySelectorAll('[data-auth-form]').forEach((form) => form.addEventListener('submit', (event) => { event.preventDefault(); const data = new FormData(form); const user = { name: data.get('register-name') || data.get('login-email').split('@')[0], email: data.get('register-email') || data.get('login-email') }; localStorage.setItem(USER_KEY, JSON.stringify(user)); showAccount(user); }));
  account.querySelector('[data-sign-out]').addEventListener('click', () => { localStorage.removeItem(USER_KEY); window.location.reload(); });
}

function init() {
  document.querySelectorAll('a').forEach((link) => { if (link.textContent.trim() === 'Archive') link.textContent = 'Admin'; });
  updateCartCount(); renderCart(); initCatalogTools(); initAdminTools(); initSiteShell(); initProfile();
  document.querySelectorAll('[data-add-to-cart]').forEach((button) => button.addEventListener('click', () => addToCart(JSON.parse(button.dataset.product))));
  const checkoutForm = document.querySelector('[data-checkout-form]');
  if (checkoutForm) checkoutForm.addEventListener('submit', (event) => { event.preventDefault(); localStorage.removeItem(CART_KEY); checkoutForm.hidden = true; document.querySelector('[data-success]').classList.add('visible'); updateCartCount(); /* [BACKEND INTEGRATION POINT] */ });
  document.querySelectorAll('.reveal').forEach((element) => new IntersectionObserver((entries, observer) => { if (entries[0].isIntersecting) { element.classList.add('is-visible'); observer.disconnect(); } }, { threshold: .12 }).observe(element));
  const parallax = document.querySelectorAll('.parallax');
  window.addEventListener('scroll', () => parallax.forEach((image) => { const offset = (window.scrollY - image.getBoundingClientRect().top) * .035; image.style.transform = `translateY(${Math.max(-18, Math.min(18, offset))}px)`; }), { passive: true });
}
document.addEventListener('DOMContentLoaded', init);

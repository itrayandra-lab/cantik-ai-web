/* ==========================================================
   navbar.js — Site Navbar (shared: layouts/header.php + homepage)
   Menu diambil dari DB via /pages/admin/api/menu.php.
   Kalau API gagal / DB down / items kosong → pakai FALLBACK_MENU
   supaya header tidak pernah kosong.
   ========================================================== */
(function () {
  var MENU_API = '/pages/admin/api/menu.php?slug=main-nav';

  /* Dipakai saat database tidak tersedia (mis. lokal tanpa MySQL).
     Tambah feature baru cukup di sini, atau pakai admin menu bila DB aktif. */
  var FALLBACK_MENU = [
    { title: 'Home', url: '/', children: [] },
    {
      title: 'AI Tools',
      url: '/feature/ai-for-cosmetic-industry',
      children: [
        { title: 'AI for Cosmetic Industry', url: '/feature/ai-for-cosmetic-industry' },
        { title: 'AI ERP', url: '/ai-erp' }
      ]
    }
  ];

  var path        = window.location.pathname.replace(/\/+$/, '') || '/';
  var hamburger   = document.getElementById('siteHamburger');
  var drawer      = document.getElementById('siteDrawer');
  var backdrop    = document.getElementById('siteBackdrop');
  var drawerItems = document.getElementById('siteDrawerItems');
  var navMenu     = document.getElementById('siteNavMenu');
  var webflowNav  = document.getElementById('nav-menu-primary');

  var isOpen = false;

  function openDrawer() {
    isOpen = true;
    drawer.classList.add('open');
    backdrop.classList.add('open');
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    isOpen = false;
    drawer.classList.remove('open');
    backdrop.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (hamburger && drawer && backdrop) {
    hamburger.addEventListener('click', function (e) {
      e.stopPropagation();
      isOpen ? closeDrawer() : openDrawer();
    });
    backdrop.addEventListener('click', closeDrawer);
  }

  /* Helper — bangun DOM tanpa innerHTML (aman dari data DB) */
  function el(tag, className, text) {
    var node = document.createElement(tag);
    if (className) node.className = className;
    if (text != null) node.textContent = text;
    return node;
  }

  function iconSrc(p) {
    if (!p) return '';
    if (/^(https?:)?\/\//.test(p)) return p;
    return '/' + p.replace(/^\/+/, '');
  }

  function applyTarget(a, item) {
    if (item.target === '_blank') {
      a.target = '_blank';
      a.rel = 'noopener';
    }
  }

  /* Exact match untuk '/', prefix match untuk sisanya */
  function isActive(url) {
    if (!url || url === '#') return false;
    var clean = url.replace(/\/+$/, '') || '/';
    if (clean === '/') return path === '/';
    return path === clean || path.indexOf(clean + '/') === 0;
  }

  function buildDropdownItem(child) {
    var a = el('a', 'dropdown__item' + (isActive(child.url) ? ' active' : ''));
    a.href = child.url;
    applyTarget(a, child);
    if (child.icon_image) {
      var img = el('img', 'dropdown__icon');
      img.src = iconSrc(child.icon_image);
      img.alt = child.icon_alt || '';
      img.loading = 'lazy';
      a.appendChild(img);
    }
    a.appendChild(el('span', 'dropdown__title', child.title));
    return a;
  }

  function closeAllDropdowns() {
    if (navMenu) {
      navMenu.querySelectorAll('li.is-open').forEach(function (li) {
        li.classList.remove('is-open');
        var trigger = li.querySelector('a');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
      });
    }
    if (webflowNav) {
      webflowNav.querySelectorAll('.dyn-menu-item.is-open').forEach(function (item) {
        item.classList.remove('is-open');
        var trigger = item.querySelector('a');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
      });
    }
  }

  /* ===== DESKTOP (.site-navbar) ===== */
  function renderDesktop(items) {
    if (!navMenu) return;
    items.forEach(function (item) {
      var children = item.children || [];
      var childActive = children.some(function (c) { return isActive(c.url); });
      var active = isActive(item.url) || childActive;

      var li = el('li', children.length ? 'has-dropdown' : '');
      if (active) li.className += (li.className ? ' ' : '') + 'is-parent-active';

      var a = el('a', active ? 'active' : '');
      a.href = item.url;
      applyTarget(a, item);
      a.appendChild(el('span', null, item.title));
      li.appendChild(a);

      if (children.length) {
        var caret = el('span', 'nav-caret', '▾');
        caret.setAttribute('aria-hidden', 'true');
        a.appendChild(caret);
        a.setAttribute('aria-haspopup', 'true');
        a.setAttribute('aria-expanded', 'false');

        var dd   = el('div', 'dropdown');
        var list = el('div', 'dropdown__inner' + (children.length > 4 ? ' dropdown__inner--grid' : ''));
        children.forEach(function (c) { list.appendChild(buildDropdownItem(c)); });
        dd.appendChild(list);
        li.appendChild(dd);

        /* Toggle via klik — untuk touch device & keyboard.
           Klik kedua (saat sudah terbuka) mengikuti link. */
        a.addEventListener('click', function (e) {
          if (li.classList.contains('is-open')) return;
          e.preventDefault();
          closeAllDropdowns();
          li.classList.add('is-open');
          a.setAttribute('aria-expanded', 'true');
        });
      }

      navMenu.appendChild(li);
    });
  }

  /* ===== MOBILE DRAWER (submenu jadi accordion) ===== */
  function renderDrawer(items) {
    if (!drawerItems) return;
    items.forEach(function (item) {
      var children = item.children || [];
      var childActive = children.some(function (c) { return isActive(c.url); });
      var wrap = el('div', 'drawer-item');

      if (children.length) {
        var btn = el('button', 'nav-drawer__toggle');
        btn.type = 'button';
        btn.setAttribute('aria-expanded', childActive ? 'true' : 'false');
        btn.appendChild(el('span', null, item.title));

        var chev = el('span', 'nav-drawer__chevron', '▾');
        chev.setAttribute('aria-hidden', 'true');
        btn.appendChild(chev);

        var sub = el('div', 'nav-drawer__sub' + (childActive ? ' open' : ''));
        children.forEach(function (c) { sub.appendChild(buildDropdownItem(c)); });

        btn.addEventListener('click', function () {
          var expanded = btn.getAttribute('aria-expanded') === 'true';
          btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
          sub.classList.toggle('open', !expanded);
        });

        wrap.appendChild(btn);
        wrap.appendChild(sub);
      } else {
        var link = el('a', 'drawer-link' + (isActive(item.url) ? ' active' : ''), item.title);
        link.href = item.url;
        applyTarget(link, item);
        wrap.appendChild(link);
      }

      drawerItems.appendChild(wrap);
    });
  }

  /* ===== WEBFLOW NAVBAR (#nav-menu-primary) — dipakai homepage & halaman statis ===== */
  function renderWebflow(items) {
    if (!webflowNav) return;
    webflowNav.innerHTML = '';

    items.forEach(function (item) {
      var children = item.children || [];
      var childActive = children.some(function (c) { return isActive(c.url); });
      var active = isActive(item.url) || childActive;

      var wrap = el('div', 'dyn-menu-item' + (children.length ? ' has-sub' : ''));

      var a = el('a', 'navbar__link w-inline-block' + (active ? ' active' : ''));
      a.href = item.url;
      applyTarget(a, item);
      if (item.icon_image) {
        var img = el('img', 'dyn-menu-icon');
        img.src = iconSrc(item.icon_image);
        img.alt = item.icon_alt || '';
        img.loading = 'lazy';
        a.appendChild(img);
      }
      a.appendChild(document.createTextNode(item.title));

      if (children.length) {
        var arrow = el('span', 'dyn-arrow', '▾');
        arrow.setAttribute('aria-hidden', 'true');
        a.appendChild(arrow);
        a.setAttribute('aria-haspopup', 'true');
        a.setAttribute('aria-expanded', 'false');

        var sub = el('div', 'dyn-submenu');
        var card = el('div', 'dyn-submenu__card');
        children.forEach(function (c) { card.appendChild(buildDropdownItem(c)); });
        sub.appendChild(card);
        wrap.appendChild(a);
        wrap.appendChild(sub);

        a.addEventListener('click', function (e) {
          if (wrap.classList.contains('is-open')) return;
          e.preventDefault();
          closeAllDropdowns();
          wrap.classList.add('is-open');
          a.setAttribute('aria-expanded', 'true');
        });
      } else {
        wrap.appendChild(a);
      }

      webflowNav.appendChild(wrap);
    });
  }

  /* Menu yang belum siap Tayangkan: disembunyikan di navbar & drawer.
     Berlaku juga untuk menu dari DB, jadi cukup ubah daftar di sini. */
  var HIDDEN_MENU = ['Blog'];

  function filterMenu(items) {
    return (items || [])
      .filter(function (item) {
        return HIDDEN_MENU.indexOf(String(item.title).toLowerCase()) === -1;
      })
      .map(function (item) {
        if (item.children && item.children.length) item.children = filterMenu(item.children);
        return item;
      });
  }

  function renderMenu(items) {
    if (navMenu) navMenu.innerHTML = '';
    if (drawerItems) drawerItems.innerHTML = '';
    var visible = filterMenu(items);
    renderDesktop(visible);
    renderDrawer(visible);
    renderWebflow(visible);
  }

  /* Klik di luar / Escape → tutup dropdown */
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.nav-menu') && !e.target.closest('.dyn-menu-item')) closeAllDropdowns();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAllDropdowns();
      if (isOpen) closeDrawer();
    }
  });

  if (drawerItems) {
    drawerItems.addEventListener('click', function (e) {
      if (e.target.closest('a')) closeDrawer();
    });
  }

  /* Render fallback dulu supaya header tidak kosong, lalu upgrade dari API */
  renderMenu(FALLBACK_MENU);

  fetch(MENU_API)
    .then(function (r) {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(function (data) {
      if (data && data.error) throw new Error(data.error);
      if (!data.items || !data.items.length) throw new Error('Menu kosong');
      renderMenu(data.items);
    })
    .catch(function () {
      /* biarkan FALLBACK_MENU yang tampil */
    });
})();

/* Bethany House redesign — interactions */

// --- mega menu / dropdown hover intent ---
document.querySelectorAll('.has-mega, .has-drop').forEach(li => {
  let t;
  li.addEventListener('mouseenter', () => { clearTimeout(t); li.classList.add('open'); });
  li.addEventListener('mouseleave', () => { t = setTimeout(() => li.classList.remove('open'), 160); });
});

// --- carousels (rail arrows) ---
document.querySelectorAll('.rail-nav button').forEach(btn => {
  btn.addEventListener('click', () => {
    const rail = document.getElementById(btn.dataset.rail);
    if (rail) rail.scrollBy({ left: 340 * Number(btn.dataset.dir), behavior: 'smooth' });
  });
});

// --- product gallery ---
const mainImg = document.getElementById('mainImg');
const thumbs = document.getElementById('thumbs');
if (mainImg && thumbs) {
  const btns = [...thumbs.querySelectorAll('button')];
  const setActive = i => {
    btns.forEach(b => b.classList.remove('active'));
    btns[i].classList.add('active');
    mainImg.src = btns[i].dataset.src;
  };
  btns.forEach((b, i) => b.addEventListener('click', () => setActive(i)));
  document.querySelectorAll('.gnav').forEach(g => g.addEventListener('click', () => {
    const cur = btns.findIndex(b => b.classList.contains('active'));
    setActive((cur + Number(g.dataset.g) + btns.length) % btns.length);
  }));
}

// --- qty stepper ---
const qval = document.getElementById('qval');
if (qval) {
  document.getElementById('qplus').onclick = () => qval.value = Number(qval.value) + 1;
  document.getElementById('qminus').onclick = () => qval.value = Math.max(1, Number(qval.value) - 1);
}

// --- finish swatches ---
document.querySelectorAll('.swatches').forEach(g => {
  g.querySelectorAll('button').forEach(b => b.addEventListener('click', () => {
    g.querySelectorAll('button').forEach(x => x.classList.remove('active'));
    b.classList.add('active');
  }));
});

// --- sticky product header + buy bar on scroll ---
const pheader = document.getElementById('pheader');
const buybar = document.getElementById('buybar');
if (pheader || buybar) {
  const onScroll = () => {
    const show = window.scrollY > 480;
    pheader && pheader.classList.toggle('show', show);
    buybar && buybar.classList.toggle('show', window.scrollY > 300);
    // active tab
    if (pheader) {
      const rev = document.getElementById('reviews');
      const inRev = rev && window.scrollY + 200 >= rev.offsetTop;
      pheader.querySelectorAll('.tabs a').forEach(a =>
        a.classList.toggle('active', (a.hash === '#reviews') === inRev));
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

// --- review star input hover fill ---
document.querySelectorAll('.rate-input .boxes').forEach(box => {
  const bs = [...box.querySelectorAll('button')];
  bs.forEach((b, i) => {
    b.addEventListener('mouseenter', () => bs.forEach((x, j) => x.textContent = j <= i ? '★' : '☆'));
    b.addEventListener('mouseleave', () => bs.forEach(x => x.textContent = '☆'));
  });
});

// --- helpful votes ---
document.querySelectorAll('.helpful button').forEach(b => b.addEventListener('click', () => {
  const m = b.textContent.match(/\((\d+)\)/);
  if (m) b.textContent = b.textContent.replace(/\(\d+\)/, `(${Number(m[1]) + 1})`);
  b.style.color = 'var(--navy-700)';
}, { once: true }));

// --- cart button feedback ---
document.querySelectorAll('.cartbtn').forEach(b => b.addEventListener('click', e => {
  e.preventDefault();
  const dot = document.querySelector('.cart-dot');
  if (dot) dot.textContent = Number(dot.textContent) + 1;
  b.style.background = 'var(--gold)';
  setTimeout(() => b.style.background = '', 500);
}));

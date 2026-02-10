<?php
/**
 * index.php — Ruby Electronics (single-page, Apple-like aesthetic)
 * No external dependencies. PHP is used only for configuration + safe output.
 */

// -------------------------
// Site configuration
// -------------------------
$siteName = "Ruby Electronics";
$tagline  = "Apple product sales, setup, trade-in guidance, and local support — Portland, OR";
$address  = "1609 NE 114th Ave, Portland, OR 97220, USA";
$phones   = ["5033882767", "5037297799"];

// -------------------------
// Helpers
// -------------------------
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function tel_clean($p) { return preg_replace('/[^0-9+]/', '', (string)$p); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= h($siteName) ?> — Apple Product Reseller & Support</title>
  <meta name="description" content="<?= h($siteName) ?>: Apple product reseller in Portland, OR. Sales, setup, trade-in guidance, and support.">
  <meta name="theme-color" content="#0b0b0f">

  <style>
    /* -------------------------
       Apple-like minimal system typography + spacing
       ------------------------- */
    :root{
      --bg: #0b0b0f;
      --bg2:#0f1116;
      --card:#121520;
      --text:#f5f5f7;
      --muted:#a1a1a6;
      --line: rgba(255,255,255,.10);
      --pill: rgba(255,255,255,.08);
      --accent:#2997ff; /* Apple-ish link blue */
      --accent2:#65c3ff;
      --shadow: 0 20px 60px rgba(0,0,0,.45);
      --radius: 22px;
      --radius2: 28px;
      --max: 1120px;
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background: radial-gradient(1200px 700px at 20% -10%, rgba(41,151,255,.18), transparent 55%),
                  radial-gradient(900px 600px at 80% 0%, rgba(101,195,255,.12), transparent 55%),
                  linear-gradient(180deg, var(--bg) 0%, var(--bg2) 60%, #0a0b10 100%);
      color:var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    a{color:var(--accent); text-decoration:none}
    a:hover{color:var(--accent2)}
    .container{max-width:var(--max); margin:0 auto; padding:0 20px}

    /* -------------------------
       Top Nav (sticky, Apple-like)
       ------------------------- */
    .nav{
      position: sticky; top:0; z-index:50;
      background: rgba(10,10,14,.70);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--line);
    }
    .nav-inner{
      display:flex; align-items:center; justify-content:space-between;
      height:56px;
    }
    .brand{
      display:flex; align-items:center; gap:10px;
      font-weight:600; letter-spacing:.2px;
      color:var(--text);
    }
    .logo{
      width:28px; height:28px; border-radius:10px;
      background: linear-gradient(135deg, rgba(255,255,255,.18), rgba(41,151,255,.18));
      border:1px solid rgba(255,255,255,.16);
      box-shadow: 0 10px 30px rgba(0,0,0,.25);
      display:grid; place-items:center;
      position:relative;
      overflow:hidden;
    }
    .logo:before{
      content:"";
      position:absolute; inset:-30%;
      background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.35), transparent 60%);
      transform: rotate(12deg);
    }
    .logo span{
      position:relative;
      font-weight:700;
      font-size:12px;
      color:rgba(255,255,255,.92);
      letter-spacing:.8px;
    }

    .nav-links{
      display:flex; align-items:center; gap:18px;
      font-size:13px;
      color:var(--muted);
    }
    .nav-links a{
      color:var(--muted);
      padding:8px 10px;
      border-radius:999px;
    }
    .nav-links a:hover{
      color:var(--text);
      background: rgba(255,255,255,.06);
    }

    .nav-cta{
      display:flex; align-items:center; gap:10px;
    }
    .btn{
      appearance:none; border:0; cursor:pointer;
      border-radius:999px;
      padding:10px 14px;
      font-weight:600; font-size:13px;
      transition: transform .15s ease, background .15s ease, color .15s ease, border-color .15s ease;
      display:inline-flex; align-items:center; gap:8px;
      white-space:nowrap;
    }
    .btn:active{transform: translateY(1px)}
    .btn-primary{
      background: var(--accent);
      color:white;
    }
    .btn-primary:hover{background:#1d86ea; color:white}
    .btn-ghost{
      background: rgba(255,255,255,.06);
      color: var(--text);
      border:1px solid rgba(255,255,255,.10);
    }
    .btn-ghost:hover{background: rgba(255,255,255,.10)}

    .burger{
      display:none;
      width:40px; height:40px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color:var(--text);
      cursor:pointer;
    }
    .burger svg{display:block; margin:auto}

    /* mobile nav drawer */
    .drawer{
      position:fixed; inset:0;
      background: rgba(0,0,0,.55);
      opacity:0; pointer-events:none;
      transition: opacity .2s ease;
      z-index:60;
    }
    .drawer.open{opacity:1; pointer-events:auto}
    .panel{
      position:absolute; top:0; right:0; height:100%; width:min(420px, 92vw);
      background: rgba(12,14,18,.92);
      border-left:1px solid rgba(255,255,255,.10);
      backdrop-filter: blur(18px);
      transform: translateX(105%);
      transition: transform .22s ease;
      padding:18px;
    }
    .drawer.open .panel{transform: translateX(0)}
    .panel a{
      display:block;
      padding:12px 12px;
      border-radius:14px;
      color:var(--text);
      border:1px solid rgba(255,255,255,.08);
      background: rgba(255,255,255,.04);
      margin-top:10px;
    }
    .panel a:hover{background: rgba(255,255,255,.08)}
    .panel .panel-top{
      display:flex; align-items:center; justify-content:space-between;
      margin-bottom:10px;
    }

    /* -------------------------
       Hero
       ------------------------- */
    .hero{
      padding: 70px 0 36px;
    }
    .hero-grid{
      display:grid;
      grid-template-columns: 1.2fr .8fr;
      gap:18px;
      align-items:stretch;
    }
    .hero-card{
      background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.04));
      border:1px solid rgba(255,255,255,.12);
      border-radius: var(--radius2);
      box-shadow: var(--shadow);
      overflow:hidden;
      position:relative;
      padding: 34px;
      min-height: 360px;
    }
    .hero-card:before{
      content:"";
      position:absolute; inset:-40%;
      background:
        radial-gradient(circle at 22% 20%, rgba(41,151,255,.26), transparent 50%),
        radial-gradient(circle at 80% 32%, rgba(255,255,255,.10), transparent 55%);
      transform: rotate(-8deg);
      pointer-events:none;
    }
    .hero-content{position:relative}
    .kicker{
      color: rgba(245,245,247,.90);
      font-size:13px;
      letter-spacing:.24em;
      text-transform:uppercase;
      margin:0 0 12px;
    }
    h1{
      margin:0;
      font-size: clamp(34px, 4.2vw, 54px);
      line-height: 1.05;
      letter-spacing:-.02em;
    }
    .subhead{
      margin:14px 0 0;
      max-width: 52ch;
      font-size: 16px;
      line-height: 1.6;
      color: rgba(245,245,247,.86);
    }
    .hero-actions{
      display:flex; flex-wrap:wrap; gap:10px;
      margin-top:22px;
    }
    .pill-row{
      display:flex; flex-wrap:wrap; gap:10px;
      margin-top:18px;
    }
    .pill{
      display:inline-flex; align-items:center; gap:8px;
      padding:10px 12px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.06);
      color: rgba(245,245,247,.90);
      font-size: 13px;
    }
    .dot{
      width:8px; height:8px; border-radius:999px;
      background: rgba(101,195,255,.95);
      box-shadow: 0 0 0 4px rgba(101,195,255,.14);
    }

    .side-card{
      border-radius: var(--radius2);
      border:1px solid rgba(255,255,255,.12);
      background: rgba(18,21,32,.65);
      box-shadow: var(--shadow);
      padding:22px;
      display:flex; flex-direction:column; gap:14px;
      min-height: 360px;
    }
    .side-title{
      font-size:18px; margin:0;
      letter-spacing:-.01em;
    }
    .side-text{
      margin:0;
      color: rgba(245,245,247,.78);
      line-height:1.6;
      font-size:14px;
    }
    .mini-grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:12px;
      margin-top: 8px;
    }
    .mini{
      border:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.05);
      border-radius: 18px;
      padding:14px;
    }
    .mini .label{color:var(--muted); font-size:12px; margin-bottom:6px}
    .mini .value{font-weight:700; letter-spacing:-.01em}
    .divider{height:1px; background: var(--line); margin:6px 0}

    /* -------------------------
       Sections
       ------------------------- */
    section{padding: 28px 0}
    .section-title{
      font-size: 24px;
      letter-spacing:-.01em;
      margin:0 0 14px;
    }
    .section-sub{
      margin:0 0 18px;
      color: rgba(245,245,247,.76);
      line-height:1.65;
      max-width: 70ch;
      font-size: 14.5px;
    }
    .grid{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }
    .card{
      background: rgba(18,21,32,.55);
      border:1px solid rgba(255,255,255,.10);
      border-radius: var(--radius);
      padding: 18px;
      box-shadow: 0 16px 50px rgba(0,0,0,.35);
    }
    .card h3{
      margin:0 0 8px;
      font-size:16px;
      letter-spacing:-.01em;
    }
    .card p{
      margin:0;
      color: rgba(245,245,247,.76);
      line-height:1.65;
      font-size: 14px;
    }
    .card ul{
      margin:12px 0 0;
      padding:0 0 0 18px;
      color: rgba(245,245,247,.72);
      line-height:1.7;
      font-size: 14px;
    }

    .cta-band{
      margin-top: 12px;
      border-radius: var(--radius2);
      border:1px solid rgba(255,255,255,.12);
      background: linear-gradient(135deg, rgba(41,151,255,.18), rgba(255,255,255,.06));
      box-shadow: var(--shadow);
      padding: 20px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      flex-wrap:wrap;
    }
    .cta-band strong{letter-spacing:-.01em}
    .cta-band span{color: rgba(245,245,247,.80); font-size:14px}

    /* -------------------------
       Contact
       ------------------------- */
    .contact-wrap{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }
    .contact-card{
      border-radius: var(--radius2);
      border:1px solid rgba(255,255,255,.12);
      background: rgba(18,21,32,.55);
      box-shadow: var(--shadow);
      padding: 20px;
    }
    .kv{
      display:grid;
      grid-template-columns: 120px 1fr;
      gap:10px 14px;
      margin-top: 10px;
      font-size: 14px;
      color: rgba(245,245,247,.78);
    }
    .kv .k{color:var(--muted)}
    .contact-actions{
      display:flex; flex-wrap:wrap; gap:10px;
      margin-top: 14px;
    }
    .note{
      margin: 12px 0 0;
      color: rgba(245,245,247,.66);
      font-size: 12.5px;
      line-height: 1.6;
    }

    /* -------------------------
       Footer
       ------------------------- */
    footer{
      padding: 28px 0 40px;
      border-top: 1px solid var(--line);
      margin-top: 20px;
      color: rgba(245,245,247,.60);
      font-size: 12.5px;
    }
    .footer-row{
      display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap;
      align-items:center;
    }

    /* -------------------------
       Responsive
       ------------------------- */
    @media (max-width: 920px){
      .hero-grid{grid-template-columns:1fr}
      .side-card{min-height:auto}
      .grid{grid-template-columns:1fr}
      .contact-wrap{grid-template-columns:1fr}
      .nav-links{display:none}
      .burger{display:inline-flex; align-items:center; justify-content:center}
    }
  </style>
</head>

<body>
  <!-- Sticky top nav -->
  <header class="nav" role="banner">
    <div class="container">
      <div class="nav-inner">
        <a class="brand" href="#top" aria-label="<?= h($siteName) ?> home">
          <span class="logo" aria-hidden="true"><span>R</span></span>
          <span><?= h($siteName) ?></span>
        </a>

        <nav class="nav-links" aria-label="Primary">
          <a href="#services">Services</a>
          <a href="#devices">Devices</a>
          <a href="#why">Why Ruby</a>
          <a href="#contact">Contact</a>
        </nav>

        <div class="nav-cta">
          <a class="btn btn-ghost" href="#contact" aria-label="View contact details">Contact</a>
          <a class="btn btn-primary" href="tel:<?= h(tel_clean($phones[0])) ?>" aria-label="Call Ruby Electronics">Call now</a>

          <button class="burger" type="button" aria-label="Open menu" id="openDrawer">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M4 7H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Mobile drawer -->
  <div class="drawer" id="drawer" aria-hidden="true">
    <div class="panel" role="dialog" aria-modal="true" aria-label="Menu">
      <div class="panel-top">
        <div class="brand">
          <span class="logo" aria-hidden="true"><span>R</span></span>
          <span><?= h($siteName) ?></span>
        </div>
        <button class="burger" type="button" aria-label="Close menu" id="closeDrawer">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
      <a href="#services">Services</a>
      <a href="#devices">Devices</a>
      <a href="#why">Why Ruby</a>
      <a href="#contact">Contact</a>
      <a href="tel:<?= h(tel_clean($phones[0])) ?>">Call now</a>
      <p class="note">Tapping a menu item will close this drawer.</p>
    </div>
  </div>

  <!-- Hero -->
  <main id="top" class="container" role="main">
    <section class="hero">
      <div class="hero-grid">
        <div class="hero-card">
          <div class="hero-content">
            <p class="kicker">Portland • Apple products</p>
            <h1>Premium Apple devices.<br>Setup & support that feels effortless.</h1>
            <p class="subhead">
              <?= h($siteName) ?> is an Apple product reseller serving Portland, OR.
              We help you choose the right device, set it up cleanly, and keep everything working smoothly.
            </p>

            <div class="hero-actions">
              <a class="btn btn-primary" href="#contact">Get help today</a>
              <a class="btn btn-ghost" href="#services">Explore services</a>
            </div>

            <div class="pill-row" aria-label="Highlights">
              <span class="pill"><span class="dot"></span>Local pickup & guidance</span>
              <span class="pill"><span class="dot"></span>Setup + data transfer</span>
              <span class="pill"><span class="dot"></span>Business-ready options</span>
            </div>
          </div>
        </div>

        <aside class="side-card" aria-label="Quick info">
          <h2 class="side-title">Fast, clear, and professional.</h2>
          <p class="side-text">
            Whether you’re upgrading an iPhone, setting up a Mac, or preparing devices for a team,
            we’ll get you from “unboxed” to “ready” with minimal friction.
          </p>
          <div class="divider"></div>

          <div class="mini-grid" role="list">
            <div class="mini" role="listitem">
              <div class="label">Location</div>
              <div class="value">Portland, OR</div>
            </div>
            <div class="mini" role="listitem">
              <div class="label">Availability</div>
              <div class="value">By call / visit</div>
            </div>
            <div class="mini" role="listitem">
              <div class="label">Primary phone</div>
              <div class="value"><a href="tel:<?= h(tel_clean($phones[0])) ?>"><?= h($phones[0]) ?></a></div>
            </div>
            <div class="mini" role="listitem">
              <div class="label">Secondary phone</div>
              <div class="value"><a href="tel:<?= h(tel_clean($phones[1])) ?>"><?= h($phones[1]) ?></a></div>
            </div>
          </div>

          <div class="cta-band" role="note" aria-label="Call to action">
            <div>
              <strong>Need a device today?</strong><br>
              <span>Call us and we’ll guide you fast.</span>
            </div>
            <a class="btn btn-primary" href="tel:<?= h(tel_clean($phones[0])) ?>">Call <?= h($phones[0]) ?></a>
          </div>
        </aside>
      </div>
    </section>

    <!-- Services -->
    <section id="services">
      <h2 class="section-title">Services</h2>
      <p class="section-sub">
        Sales + support designed to feel like an Apple Store experience, without the wait.
        Clear options, tidy setup, and practical help you can trust.
      </p>

      <div class="grid">
        <div class="card">
          <h3>Device Sales & Recommendations</h3>
          <p>Help picking the right iPhone, iPad, Mac, or accessories for your needs and budget.</p>
          <ul>
            <li>Personal use, creators, students, business</li>
            <li>Accessories guidance (cases, chargers, keyboards)</li>
            <li>Simple options, no confusing jargon</li>
          </ul>
        </div>

        <div class="card">
          <h3>Setup & Data Transfer</h3>
          <p>Move your data safely and set up the essentials so you’re ready to work immediately.</p>
          <ul>
            <li>iPhone-to-iPhone transfer</li>
            <li>Mac migration & account setup</li>
            <li>Apps, email, and backups configured</li>
          </ul>
        </div>

        <div class="card">
          <h3>Troubleshooting & Support</h3>
          <p>Practical help for common issues—performance, storage, connectivity, and more.</p>
          <ul>
            <li>Wi-Fi / Bluetooth / iCloud issues</li>
            <li>Performance & storage clean-up</li>
            <li>Guided settings and best practices</li>
          </ul>
        </div>
      </div>

      <div class="cta-band">
        <div>
          <strong>Want help choosing the right device?</strong><br>
          <span>Call and tell us what you need. We’ll recommend a clean setup.</span>
        </div>
        <a class="btn btn-primary" href="tel:<?= h(tel_clean($phones[0])) ?>">Call now</a>
      </div>
    </section>

    <!-- Devices -->
    <section id="devices">
      <h2 class="section-title">Devices we support</h2>
      <p class="section-sub">
        We specialize in Apple devices and the workflows people actually use—photos, messaging,
        business email, productivity, creative tools, and secure backups.
      </p>

      <div class="grid">
        <div class="card">
          <h3>iPhone</h3>
          <p>Upgrade guidance, migration, iCloud setup, and everyday support.</p>
        </div>
        <div class="card">
          <h3>iPad</h3>
          <p>Work + study setups, accessories guidance, and app configuration.</p>
        </div>
        <div class="card">
          <h3>Mac</h3>
          <p>Migration, accounts, productivity setup, and performance tuning.</p>
        </div>
      </div>
    </section>

    <!-- Why Ruby -->
    <section id="why">
      <h2 class="section-title">Why Ruby Electronics</h2>
      <p class="section-sub">
        A reseller should be more than a transaction. Our goal is simple: you leave with a device that’s
        set up correctly, your data protected, and a clear next step if you need support.
      </p>

      <div class="grid">
        <div class="card">
          <h3>Apple-like simplicity</h3>
          <p>Clean recommendations, clear setup steps, and no confusion.</p>
        </div>
        <div class="card">
          <h3>Local and responsive</h3>
          <p>Portland-based support—call and get help without the long queues.</p>
        </div>
        <div class="card">
          <h3>Practical security</h3>
          <p>Backups, account hygiene, and settings that keep your data safer.</p>
        </div>
      </div>
    </section>

    <!-- Contact -->
    <section id="contact" aria-label="Contact">
      <h2 class="section-title">Contact</h2>
      <p class="section-sub">
        Call us for availability, device questions, or support needs. If you’re nearby, you can also visit.
      </p>

      <div class="contact-wrap">
        <div class="contact-card">
          <h3 style="margin:0 0 10px; font-size:16px;">Ruby Electronics</h3>
          <div class="kv" role="list">
            <div class="k" role="listitem">Address</div>
            <div><?= h($address) ?></div>

            <div class="k" role="listitem">Phone</div>
            <div>
              <a href="tel:<?= h(tel_clean($phones[0])) ?>"><?= h($phones[0]) ?></a>
              <span style="color:rgba(245,245,247,.45)"> / </span>
              <a href="tel:<?= h(tel_clean($phones[1])) ?>"><?= h($phones[1]) ?></a>
            </div>

            <div class="k" role="listitem">Services</div>
            <div>Sales • Setup • Migration • Support</div>
          </div>

          <div class="contact-actions">
            <a class="btn btn-primary" href="tel:<?= h(tel_clean($phones[0])) ?>">Call <?= h($phones[0]) ?></a>
            <a class="btn btn-ghost" href="tel:<?= h(tel_clean($phones[1])) ?>">Call <?= h($phones[1]) ?></a>
          </div>

          <p class="note">
            Tip: On desktop, clicking “Call” may open a calling app (FaceTime/Skype) depending on your system.
            On mobile, it opens the dialer.
          </p>
        </div>

        <div class="contact-card">
          <h3 style="margin:0 0 10px; font-size:16px;">What to share on the call</h3>
          <p style="margin:0; color:rgba(245,245,247,.76); line-height:1.65; font-size:14px;">
            For faster help, tell us:
          </p>
          <ul style="margin:12px 0 0; color:rgba(245,245,247,.72); line-height:1.75; font-size:14px;">
            <li>Which device you have (or want)</li>
            <li>What you’re trying to do (work, school, photos, business email, etc.)</li>
            <li>Whether you need data transfer / setup</li>
            <li>Any urgent timeline</li>
          </ul>

          <div class="cta-band" style="margin-top:16px;">
            <div>
              <strong>Ready to get started?</strong><br>
              <span>We’ll guide you step-by-step.</span>
            </div>
            <a class="btn btn-primary" href="tel:<?= h(tel_clean($phones[0])) ?>">Call now</a>
          </div>
        </div>
      </div>
    </section>

    <footer role="contentinfo">
      <div class="footer-row">
        <div>© <?= date('Y') ?> <?= h($siteName) ?>. All rights reserved.</div>
        <div>
          <a href="#top">Back to top</a>
          <span style="color:rgba(245,245,247,.35)"> • </span>
          <a href="#contact">Contact</a>
        </div>
      </div>
    </footer>
  </main>

  <script>
    // Minimal JS: drawer + smooth scroll (Apple-like)
    (function () {
      const drawer = document.getElementById("drawer");
      const openBtn = document.getElementById("openDrawer");
      const closeBtn = document.getElementById("closeDrawer");

      function openDrawer() {
        drawer.classList.add("open");
        drawer.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
      }
      function closeDrawer() {
        drawer.classList.remove("open");
        drawer.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
      }

      openBtn && openBtn.addEventListener("click", openDrawer);
      closeBtn && closeBtn.addEventListener("click", closeDrawer);
      drawer && drawer.addEventListener("click", (e) => {
        if (e.target === drawer) closeDrawer();
      });

      // Close drawer when a menu link is clicked
      document.querySelectorAll(".panel a[href^='#']").forEach(a => {
        a.addEventListener("click", closeDrawer);
      });

      // Smooth scroll
      document.addEventListener("click", (e) => {
        const a = e.target.closest("a[href^='#']");
        if (!a) return;
        const id = a.getAttribute("href");
        if (!id || id === "#") return;
        const el = document.querySelector(id);
        if (!el) return;
        e.preventDefault();
        el.scrollIntoView({behavior:"smooth", block:"start"});
        history.replaceState(null, "", id);
      });
    })();
  </script>
</body>
</html>

<?php
require __DIR__ . '/config.php';
try {
    $skills   = db()->query('SELECT * FROM skills ORDER BY sort_order, id')->fetchAll();
    $projects = db()->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
} catch (Throwable $t) {
    http_response_code(500);
    exit('<h2 style="font-family:sans-serif">Database not ready 🎀</h2><p style="font-family:sans-serif">Start MySQL in XAMPP and import database.sql in phpMyAdmin.</p>');
}
$cats = array_values(array_unique(array_column($projects, 'category')));
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#ff77ad">
<title>Manovarshini | Little World on the Web</title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎀</text></svg>">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&family=Pacifico&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="bar"></div>
<div class="app">
<header>
  <div class="brand">🎀 Manovarshini’s portfolio</div>
  <button id="theme" aria-label="Toggle theme">🌙</button>
  <nav id="nav"></nav>
</header>

<main id="main">
<section id="home" class="in">
  <span class="tag">✦ tiny steps, big dreams ✦</span>
  <div class="float">🎀⭐🧸</div>
  <div>
    <small>HELLO, I’M</small>
    <h1>Manovarshini M.</h1>
    <div class="sub"><span id="type"></span></div>
  </div>
  <p>2nd-year CSE student at Sri Krishna College of Engineering and Technology, building my foundation to become a Data Analyst. Passionate about learning SQL, Python, and turning data into meaningful insights.</p>
  <div class="btns">
    <button class="btn" data-go="3">Explore my work ↗</button>
    <button class="btn alt" data-go="1">About me ☁</button>
  </div>
  <div class="scene" id="scene">
    <span class="e e1">🎀</span><span class="e e2">⭐</span><span class="e e3">🧸</span><span class="e e4">🌼</span>
    <span class="e e5">✧</span><span class="e e6">✿</span><span class="e e7">🫧</span><span class="e e8">🦋</span>
    <div class="lap">💻</div>
    <div class="face">૮ ˶ᵔ ᵕ ᵔ˶ ა</div>
    <div class="dream" id="quote">dream it · do it ♡</div>
  </div>
  <span class="count">01 / 05 · tap the scene ✨</span>
</section>

<section id="about">
  <div class="float">🌷🦋☁️</div>
  <h2>A little <em>about me</em></h2>
  <small>Curious mind, creative heart, and a whole lot left to learn.</small>
  <div class="card"><h3>🌸 Hi, I’m Mano!</h3><p>I’m a second-year B.E. CSE student at SKCET. I enjoy exploring how technology works and turning ideas into something real. Right now I’m growing my skills in programming, web design, and data analysis — one practice session at a time.</p></div>
  <div class="grid">
    <div class="card"><h3>🎓 Currently</h3><p>B.E. Computer Science<br>SKCET · 2025–2029</p></div>
    <div class="card"><h3>🔎 Curious about</h3><p>Data, patterns, technology, and creative problem-solving.</p></div>
    <div class="card"><h3>🌱 My goal</h3><p>Become a skilled data analyst and keep building useful things.</p></div>
    <div class="card"><h3>☕ My motto</h3><p>Small progress is still progress. Keep showing up!</p></div>
  </div>
  <span class="count">02 / 05</span>
</section>

<section id="skills">
  <div class="float">🪄🍓</div>
  <small>MY LITTLE TOOLBOX</small>
  <h2>Learning, one <em>skill</em> at a time</h2>
  <small>Loaded live from MySQL · tap a card to see what I’m up to.</small>
  <div class="grid skills">
    <?php foreach ($skills as $s): ?>
    <div class="card" data-m="<?= e($s['message']) ?>">
      <div class="ic"><?= e($s['icon']) ?></div>
      <h3><?= e($s['name']) ?></h3>
      <p><?= e($s['description']) ?></p>
      <div class="lvl"><i style="--w:<?= (int)$s['level'] ?>%"></i></div>
    </div>
    <?php endforeach; ?>
  </div>
  <p class="note" id="skillnote">🌱 Next up: Excel, data visualisation, and more hands-on projects!</p>
  <span class="count">03 / 05</span>
</section>

<section id="projects">
  <div class="float">🪩🍒</div>
  <small>IDEAS TURNING INTO THINGS</small>
  <h2>My tiny <em>project shelf</em></h2>
  <div class="filters" id="filters">
    <button class="on" data-f="all">All</button>
    <?php foreach ($cats as $c): ?><button data-f="<?= e($c) ?>"><?= e(ucfirst($c)) ?></button><?php endforeach; ?>
  </div>
  <?php foreach ($projects as $p): ?>
  <div class="card proj" data-c="<?= e($p['category']) ?>">
    <small><?= e($p['label']) ?></small>
    <h3><?= e($p['title']) ?></h3>
    <p><?= e($p['description']) ?></p>
    <div class="chips"><?php foreach (array_filter(array_map('trim', explode(',', $p['tags']))) as $t): ?><span><?= e($t) ?></span><?php endforeach; ?></div>
  </div>
  <?php endforeach; ?>
  <p class="note">More projects coming soon… the idea jar is never empty! 🫙</p>
  <span class="count">04 / 05</span>
</section>

<section id="contact">
  <div class="float">💌🎀🌟</div>
  <small>LET’S SAY HELLO</small>
  <h2>Have a lovely <em>idea?</em></h2>
  <p>I’m open to learning opportunities, internships, collaborations, and meeting people who love building and learning as much as I do.</p>
  <form id="form">
    <input name="name" placeholder="Your name" maxlength="80" required>
    <input name="email" type="email" placeholder="Your email" required>
    <textarea name="message" placeholder="Write a little note…" maxlength="2000" required></textarea>
    <div class="btns"><button class="btn" type="submit">Send my note 💌</button></div>
    <p class="msg" id="formmsg"></p>
  </form>
  <span class="count">05 / 05</span>
</section>
</main>
<footer>🎀 ✿ ✧ 🌷 ✧ ✿ 🎀</footer>
</div>
<script src="app.js"></script>
</body>
</html>

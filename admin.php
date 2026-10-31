<?php
require __DIR__ . '/config.php';
session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

$err = '';
if (isset($_GET['logout'])) { session_destroy(); header('Location: admin.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad token'); }
    $a = $_POST['action'] ?? '';
    if ($a === 'login') {
        if (hash_equals(ADMIN_PASSWORD, $_POST['password'] ?? '')) { $_SESSION['ok'] = true; session_regenerate_id(true); $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
        else $err = 'Wrong password';
    } elseif (!empty($_SESSION['ok'])) {
        $pdo = db();
        if ($a === 'add_skill') {
            $pdo->prepare('INSERT INTO skills (name,icon,description,level,message,sort_order) VALUES (?,?,?,?,?,?)')
                ->execute([$_POST['name'], $_POST['icon'], $_POST['description'], max(0, min(100, (int)$_POST['level'])), $_POST['message'], (int)$_POST['sort_order']]);
        } elseif ($a === 'del_skill') {
            $pdo->prepare('DELETE FROM skills WHERE id=?')->execute([(int)$_POST['id']]);
        } elseif ($a === 'add_project') {
            $pdo->prepare('INSERT INTO projects (category,label,title,description,tags,sort_order) VALUES (?,?,?,?,?,?)')
                ->execute([strtolower(trim($_POST['category'])), $_POST['label'], $_POST['title'], $_POST['description'], $_POST['tags'], (int)$_POST['sort_order']]);
        } elseif ($a === 'del_project') {
            $pdo->prepare('DELETE FROM projects WHERE id=?')->execute([(int)$_POST['id']]);
        } elseif ($a === 'del_msg') {
            $pdo->prepare('DELETE FROM messages WHERE id=?')->execute([(int)$_POST['id']]);
        }
        header('Location: admin.php'); exit;
    }
}
$csrf = '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
?><!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin | Manovarshini</title>
<style>
body{font-family:system-ui,sans-serif;background:#fff0f6;color:#4a2c3d;max-width:900px;margin:0 auto;padding:16px}
h1,h2{color:#e0467f}.card{background:#fff;border:1.5px solid #ffc2da;border-radius:14px;padding:12px;margin:10px 0}
input,textarea{padding:7px;border:1.5px solid #ffc2da;border-radius:10px;font:inherit;margin:3px 0;width:100%;box-sizing:border-box}
button{background:#ff77ad;color:#fff;border:0;border-radius:99px;padding:7px 14px;font:inherit;font-weight:700;cursor:pointer}
button.del{background:#8a5a74;padding:3px 10px}table{width:100%;border-collapse:collapse}td{padding:6px;border-bottom:1px solid #ffc2da;vertical-align:top}
.row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:6px}
</style></head><body>
<h1>🎀 Admin panel</h1>
<?php if (empty($_SESSION['ok'])): ?>
  <div class="card"><form method="post"><?= $csrf ?><input type="hidden" name="action" value="login">
    <input type="password" name="password" placeholder="Admin password" required>
    <button>Log in</button> <span style="color:#c00"><?= e($err) ?></span></form></div>
<?php else:
  $pdo = db();
  $skills = $pdo->query('SELECT * FROM skills ORDER BY sort_order,id')->fetchAll();
  $projects = $pdo->query('SELECT * FROM projects ORDER BY sort_order,id')->fetchAll();
  $msgs = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
?>
<p><a href="index.php">← View site</a> · <a href="?logout=1">Log out</a></p>

<h2>💌 Messages (<?= count($msgs) ?>)</h2>
<?php foreach ($msgs as $m): ?>
  <div class="card"><b><?= e($m['name']) ?></b> &lt;<a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a>&gt; · <small><?= e($m['created_at']) ?></small>
  <p><?= nl2br(e($m['message'])) ?></p>
  <form method="post"><?= $csrf ?><input type="hidden" name="action" value="del_msg"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button class="del">Delete</button></form></div>
<?php endforeach; if (!$msgs) echo '<p>No messages yet.</p>'; ?>

<h2>✧ Skills</h2>
<div class="card"><table>
<?php foreach ($skills as $s): ?><tr><td><?= e($s['icon']) ?> <b><?= e($s['name']) ?></b> — <?= e($s['description']) ?> (<?= (int)$s['level'] ?>%)</td>
<td><form method="post"><?= $csrf ?><input type="hidden" name="action" value="del_skill"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>"><button class="del">Delete</button></form></td></tr><?php endforeach; ?>
</table>
<form method="post"><?= $csrf ?><input type="hidden" name="action" value="add_skill"><div class="row">
<input name="name" placeholder="Name (e.g. Python)" required><input name="icon" placeholder="Icon (e.g. 🐍)" required>
<input name="description" placeholder="Short description" required><input name="level" type="number" min="0" max="100" placeholder="Level 0-100" required>
<input name="message" placeholder="Message when tapped" required><input name="sort_order" type="number" placeholder="Order" value="10"></div>
<button>Add skill</button></form></div>

<h2>🧸 Projects</h2>
<div class="card"><table>
<?php foreach ($projects as $p): ?><tr><td><b><?= e($p['title']) ?></b> <small>[<?= e($p['category']) ?>]</small></td>
<td><form method="post"><?= $csrf ?><input type="hidden" name="action" value="del_project"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="del">Delete</button></form></td></tr><?php endforeach; ?>
</table>
<form method="post"><?= $csrf ?><input type="hidden" name="action" value="add_project"><div class="row">
<input name="title" placeholder="Title" required><input name="category" placeholder="Category (web, social, data…)" required>
<input name="label" placeholder="Label (e.g. 📊 DATA · SQL)" required><input name="tags" placeholder="Tags, comma separated" required>
<input name="sort_order" type="number" placeholder="Order" value="10"></div>
<textarea name="description" placeholder="Description" required></textarea><button>Add project</button></form></div>
<?php endif; ?>
</body></html>

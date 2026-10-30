<?php
// Shared navigation for all authenticated application pages.
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$isCvPage = in_array($currentPage, ['preview.php', 'edit-cv.php'], true);
$items = [
    ['dashboard.php', '⌂', 'Dashboard', ['dashboard.php']],
    ['dashboard.php#my-cvs', '▤', 'CV của tôi', ['preview.php', 'edit-cv.php']],
    ['create-cv-chat.php', '✦', 'Tạo CV với AI', ['create-cv-chat.php', 'create-cv-form.php']],
    ['cv-templates.php', '▣', 'Mẫu CV', ['cv-templates.php', 'mau-cv.php']],
    ['account.php', '♙', 'Tài khoản', ['account.php']],
];
?>
<aside class="sidebar">
  <div class="logo"><a href="home.php">✦ CV<span>AI</span></a></div>
  <nav aria-label="Điều hướng chính">
    <p class="menu-title">MENU</p>
    <?php foreach ($items as [$href, $icon, $label, $pages]): ?>
      <a class="menu-item<?= in_array($currentPage, $pages, true) ? ' active' : '' ?>" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"><span class="icon"><?= $icon ?></span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-bottom"><a class="menu-item logout" href="logout.php"><span class="icon">↪</span>Đăng xuất</a></div>
</aside>

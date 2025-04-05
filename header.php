<nav class="navbar">
    <h2 style="margin: 0.5%; padding: 0% 0.5%;"><a href="index.php" class="navbar-logo">CapyGames</a></h2>
    <ul class="navbar-menu"  style="margin: 0%;">
    <?php if (isset($_SESSION['username'])): ?>
        <a href="profile.php" class="nav-item">Profile</a>
         <a href="view_cart.php" class="nav-item">Cart</a>
          <a href="../API/logout.php" class="nav-item">Log out</a>
    <?php else: ?>
        <a href="log.php" class="nav-item">Log in</a>
        <a href="reg.php" class="nav-item">Sign up</a>
    <?php endif; ?>
    </ul>
</nav>
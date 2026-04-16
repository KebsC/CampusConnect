<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="?route=feed" class="navbar-brand fw-bold">Campus Connect</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form class="d-flex mx-auto my-2 my-md-0" method="GET" action="">
                    <input type="hidden" name="route" value="search">
                    <input class="form-control me-2" type="search" name="q"
                        placeholder="Search users..."
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>

                <div class="dropdown">
                    <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        href="#" role="button" data-bs-toggle="dropdown">
                        <img src="<?= UPLOADS_URL ?>/<?= htmlspecialchars($_SESSION['profile_image'] ?? 'default.png') ?>"
                            alt="Profile" class="rounded-circle me-2" width="35" height="35" style="object-fit:cover;">
                        <span><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="?route=profile">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?route=logout">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="ms-auto d-flex gap-2">
                    <a href="?route=login" class="btn btn-primary">Login</a>
                    <a href="?route=signup" class="btn btn-secondary">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

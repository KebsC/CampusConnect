<?php if (!isset($_SESSION['user_id'])) {
    header("Location: ?route=login");
    exit;
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="container mt-4" style="max-width:680px;">
        <h5>Search Results for: <strong><?= htmlspecialchars($query) ?></strong></h5>
        <hr>
        <?php if (empty($results)): ?>
            <p class="text-muted">No users found.</p>
        <?php else: ?>
            <?php foreach ($results as $user): ?>
                <a href="?route=<?= $user['id'] == $_SESSION['user_id'] ? 'profile' : 'user&user_id=' . $user['id'] ?>"
                    class="text-decoration-none text-dark">
                    <div class="d-flex align-items-center p-3 mb-2 border rounded">
                        <img src="<?= UPLOADS_URL ?>/<?= htmlspecialchars($user['profile_image'] ?? 'default.png') ?>"
                            class="rounded-circle me-3" width="50" height="50" style="object-fit:cover;">
                        <div>
                            <strong><?= htmlspecialchars($user['username']) ?></strong>
                            <?php if (!empty($user['full_name'])): ?>
                                <div class="text-muted small"><?= htmlspecialchars($user['full_name']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ?route=login");
    exit;
}

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../../config/database.php';

$userId = (int) ($_GET['user_id'] ?? 0);
if ($userId === (int) $_SESSION['user_id']) {
    header("Location: ?route=profile");
    exit;
}

$userModel = new UserModel();
$user = $userModel->findUserById($userId);
if (!$user) {
    header("Location: ?route=feed");
    exit;
}

$postModel = new PostModel($conn);
$posts = $postModel->getPostsByUser($user['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']) ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="container mt-4" style="max-width:680px;">
        <div class="card text-center mb-4">
            <div class="card-body">
                <img src="<?= UPLOADS_URL ?>/<?= htmlspecialchars($user['profile_image'] ?? 'default.png') ?>"
                    alt="Profile" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover;">
                <h4><?= htmlspecialchars($user['username']) ?></h4>
                <?php if (!empty($user['full_name'])): ?>
                    <p class="text-muted mb-1"><?= htmlspecialchars($user['full_name']) ?></p>
                <?php endif; ?>
                <p class="text-muted"><?= !empty($user['bio']) ? htmlspecialchars($user['bio']) : 'No bio yet.' ?></p>
            </div>
        </div>

        <h5><?= htmlspecialchars($user['username']) ?>'s Posts</h5>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3">
                    <div class="card-body d-flex">
                        <img src="<?= UPLOADS_URL ?>/<?= htmlspecialchars($user['profile_image'] ?? 'default.png') ?>"
                            alt="Profile" class="rounded-circle me-2 flex-shrink-0" width="40" height="40"
                            style="object-fit:cover;">
                        <div>
                            <strong><?= htmlspecialchars($user['username']) ?></strong>
                            <?php if (!empty($post['created_at'])): ?>
                                <div class="text-muted" style="font-size:0.75rem;">
                                    <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?></div>
                            <?php endif; ?>
                            <p class="mb-1 mt-1"><?= htmlspecialchars($post['content']) ?></p>
                            <small class="text-muted"><?= $post['likes'] ?? 0 ?> Likes</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">This user hasn't posted anything yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>
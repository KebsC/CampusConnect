<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
    <?php
    include __DIR__ . '/partials/navbar.php';
    if (!isset($posts) || !is_array($posts))
        $posts = [];
    ?>

    <div class="container mt-4" style="max-width:680px;">
        <form method="POST" action="?route=create_post" class="mb-4">
            <textarea name="content" class="form-control mb-2" rows="3" placeholder="What's on your mind?"
                required></textarea>
            <button class="btn btn-primary w-100">Post</button>
        </form>

        <?php if (empty($posts)): ?>
            <p class="text-center text-muted">No posts yet. Be the first to post!</p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <a href="?route=user&user_id=<?= $post['user_id'] ?>">
                            <img src="<?= UPLOADS_URL ?>/<?= htmlspecialchars($post['profile_image'] ?? 'default.png') ?>"
                                alt="Profile" class="rounded-circle me-2" width="40" height="40" style="object-fit:cover;">
                        </a>
                        <div class="flex-grow-1">
                            <a href="?route=user&user_id=<?= $post['user_id'] ?>"
                                class="text-decoration-none text-dark fw-semibold">
                                <?= htmlspecialchars($post['username']) ?>
                            </a>
                            <div class="text-muted" style="font-size:0.75rem;">
                                <?= $post['created_at'] ? date('M j, Y g:i A', strtotime($post['created_at'])) : 'Just now' ?>
                            </div>
                        </div>
                    </div>

                    <p class="mb-2"><?= htmlspecialchars($post['content']) ?></p>
                    <p class="text-muted mb-2" style="font-size:0.85rem;"><?= $post['likes'] ?? 0 ?> Likes</p>

                    <div class="d-flex mb-3 gap-2">
                        <a href="?route=like&post_id=<?= $post['id'] ?>" class="btn btn-primary btn-sm flex-fill">Like</a>
                        <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                            <a href="?route=edit_post&post_id=<?= $post['id'] ?>"
                                class="btn btn-secondary btn-sm flex-fill">Edit</a>
                            <a href="?route=delete_post&post_id=<?= $post['id'] ?>" class="btn btn-danger btn-sm flex-fill"
                                onclick="return confirm('Delete this post?')">Delete</a>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="?route=add_comment&post_id=<?= $post['id'] ?>">
                        <textarea name="comment" class="form-control mb-2" rows="2"
                            placeholder="Write a comment..."></textarea>
                        <button class="btn btn-primary btn-sm w-100">Post Comment</button>
                    </form>

                    <?php if (!empty($post['comments'])): ?>
                        <div class="mt-3">
                            <?php foreach ($post['comments'] as $comment): ?>
                                <div class="d-flex mb-2 align-items-start">
                                    <img src="<?= UPLOADS_URL ?>/<?= htmlspecialchars($comment['profile_image'] ?? 'default.png') ?>"
                                        class="rounded-circle me-2 flex-shrink-0" width="30" height="30" style="object-fit:cover;">
                                    <div class="bg-light p-2 rounded flex-grow-1">
                                        <div class="d-flex justify-content-between flex-wrap">
                                            <strong
                                                style="font-size:0.85rem;"><?= htmlspecialchars($comment['username']) ?></strong>
                                            <small
                                                class="text-muted"><?= $comment['created_at'] ? date('M j, Y g:i A', strtotime($comment['created_at'])) : 'Just now' ?></small>
                                        </div>
                                        <div style="font-size:0.9rem;"><?= htmlspecialchars($comment['content']) ?></div>
                                    </div>
                                    <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                                        <a href="?route=edit_comment&comment_id=<?= $comment['id'] ?>"
                                            class="btn btn-sm btn-link text-primary ms-1">✎</a>
                                        <a href="?route=delete_comment&comment_id=<?= $comment['id'] ?>"
                                            class="btn btn-sm btn-link text-danger"
                                            onclick="return confirm('Delete this comment?')">✕</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
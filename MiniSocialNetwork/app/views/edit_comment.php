<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container mt-4" style="max-width:680px;">
        <h3>Edit Comment</h3>
        <form method="POST" action="?route=update_comment">
            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
            <textarea name="content" class="form-control mb-3" rows="4"
                required><?= htmlspecialchars($comment['content']) ?></textarea>
            <button class="btn btn-primary w-100">Update Comment</button>
        </form>
    </div>
</body>

</html>
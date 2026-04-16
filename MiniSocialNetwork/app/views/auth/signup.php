<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100 px-3">
        <div class="w-100 p-4 shadow rounded bg-white" style="max-width: 480px;">
            <h2 class="text-center mb-4">Sign Up</h2>
            <form method="POST" action="?route=signup">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm-password" placeholder="Confirm your password" required>
                </div>
                <p>Already have an account? <a href="?route=login">Login here</a></p>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
session_start();
date_default_timezone_set('Asia/Manila');

$isLocal = $_SERVER['SERVER_NAME'] === 'localhost';
define('BASE_URL', $isLocal ? '/MiniSocialNetwork/public' : '');
define('UPLOADS_URL', BASE_URL . '/uploads');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/FeedController.php';

$route = $_GET['route'] ?? '';

$auth = new AuthController();
$feed = new FeedController();

switch ($route) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login();
        } else {
            require __DIR__ . '/../app/views/auth/login.php';
        }
        break;

    case 'signup':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->signup();
        } else {
            require __DIR__ . '/../app/views/auth/signup.php';
        }
        break;

    case 'logout':
        $auth->logout();
        break;

    case 'feed':
        $feed->feed();
        break;

    case 'create_post':
        $feed->createPost();
        break;

    case 'like':
        $feed->like();
        break;

    case 'add_comment':
        $feed->addComment();
        break;

    case 'edit_comment':
        $feed->editComment();
        break;

    case 'update_comment':
        $feed->updateComment();
        break;

    case 'edit_post':
        $feed->editPost();
        break;

    case 'update_post':
        $feed->updatePost();
        break;

    case 'delete_post':
        $feed->deletePost();
        break;

    case 'delete_comment':
        $feed->deleteComment();
        break;

    case 'profile':
        $feed->profile();
        break;

    case 'user':
        require __DIR__ . '/../app/views/user_profile.php';
        break;

    case 'edit_profile':
        $feed->editProfile();
        break;

    case 'search':
        $feed->search();
        break;

    default:
        require __DIR__ . '/../app/views/auth/login.php';
        break;
}

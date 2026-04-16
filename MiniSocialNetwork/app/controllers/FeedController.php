<?php
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class FeedController
{
    /** @var PostModel */
    private $postModel;

    public function __construct()
    {
        global $conn;
        $this->postModel = new PostModel($conn);
    }

    private function redirect($route)
    {
        $base = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
        header("Location: $base?route=$route");
        exit;
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    public function feed()
    {
        $this->requireLogin();
        $posts = $this->postModel->getAllPosts();
        foreach ($posts as &$post) {
            $post['comments'] = $this->postModel->getCommentsByPost($post['id']);
        }
        unset($post);
        include __DIR__ . '/../views/feed.php';
    }

    public function profile()
    {
        $this->requireLogin();
        $userModel = new UserModel();
        $user = $userModel->findUserById($_SESSION['user_id']);
        $posts = $this->postModel->getPostsByUser($user['id']);
        include __DIR__ . '/../views/profile.php';
    }

    public function createPost()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content']);
            if ($content !== '') {
                $this->postModel->insertPost($_SESSION['user_id'], $content);
            }
        }
        $this->redirect('feed');
    }

    public function deletePost()
    {
        $this->requireLogin();
        if (isset($_GET['post_id'])) {
            $this->postModel->deletePost((int) $_GET['post_id'], $_SESSION['user_id']);
        }
        $this->redirect('feed');
    }

    public function like()
    {
        $this->requireLogin();
        if (isset($_GET['post_id'])) {
            $this->postModel->likePost($_SESSION['user_id'], (int) $_GET['post_id']);
        }
        $this->redirect('feed');
    }

    public function addComment()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['post_id'])) {
            $comment = trim($_POST['comment']);
            if ($comment !== '') {
                $this->postModel->addComment($_SESSION['user_id'], (int) $_GET['post_id'], $comment);
            }
        }
        $this->redirect('feed');
    }

    public function editComment()
    {
        $this->requireLogin();
        $comment = $this->postModel->getCommentById((int) $_GET['comment_id']);
        if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
            $this->redirect('feed');
        }
        include __DIR__ . '/../views/edit_comment.php';
    }

    public function updateComment()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = (int) $_POST['comment_id'];
            $content = trim($_POST['content']);
            $comment = $this->postModel->getCommentById($commentId);
            if ($comment && $comment['user_id'] == $_SESSION['user_id'] && $content !== '') {
                $this->postModel->updateComment($commentId, $content);
            }
        }
        $this->redirect('feed');
    }

    public function deleteComment()
    {
        $this->requireLogin();
        if (isset($_GET['comment_id'])) {
            $this->postModel->deleteComment((int) $_GET['comment_id'], $_SESSION['user_id']);
        }
        $this->redirect('feed');
    }

    public function editPost()
    {
        $this->requireLogin();
        $post = $this->postModel->getPostById((int) $_GET['post_id']);
        if (!$post || $post['user_id'] != $_SESSION['user_id']) {
            $this->redirect('feed');
        }
        include __DIR__ . '/../views/edit_post.php';
    }

    public function updatePost()
    {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = (int) $_POST['post_id'];
            $content = trim($_POST['content']);
            $post = $this->postModel->getPostById($postId);
            if ($post && $post['user_id'] == $_SESSION['user_id'] && $content !== '') {
                $this->postModel->updatePost($postId, $content);
            }
        }
        $this->redirect('feed');
    }

    public function editProfile()
    {
        $this->requireLogin();
        $userModel = new UserModel();
        $user = $userModel->findUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name']);
            $username = trim($_POST['username']);
            $bio = trim($_POST['bio'] ?? '');

            $profile_image = null;
            if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === 0) {
                $filename = time() . '_' . basename($_FILES['profile_image']['name']);
                $dest = __DIR__ . "/../../public/uploads/$filename";
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
                    $profile_image = $filename;
                }
            }

            $userModel->updateProfile($_SESSION['user_id'], $full_name, $username, $bio, $profile_image);
            $_SESSION['username'] = $username;
            if ($profile_image)
                $_SESSION['profile_image'] = $profile_image;
            $this->redirect('profile');
        }

        include __DIR__ . '/../views/edit_profile.php';
    }

    public function search()
    {
        $this->requireLogin();
        $query = trim($_GET['q'] ?? '');
        $results = [];
        if ($query !== '') {
            $userModel = new UserModel();
            $results = $userModel->searchByUsername($query);
        }
        include __DIR__ . '/../views/search.php';
    }
}

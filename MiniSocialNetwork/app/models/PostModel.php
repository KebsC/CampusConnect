<?php
class PostModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllPosts()
    {
        $sql = "SELECT DISTINCT posts.id, posts.content, posts.user_id, posts.created_at,
            users.username, users.profile_image,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS likes
            FROM posts
            JOIN users ON posts.user_id = users.id
            ORDER BY posts.created_at DESC";
        $result = $this->conn->query($sql);
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }

    public function insertPost($userId, $content)
    {
        $stmt = $this->conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $content);
        $stmt->execute();
        $stmt->close();
    }

    public function deletePost($postId, $userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function likePost($userId, $postId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            $stmt = $this->conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
        } else {
            $stmt = $this->conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        }
        $stmt->bind_param("ii", $userId, $postId);
        $stmt->execute();
        $stmt->close();
    }

    public function getPostsByUser($user_id)
    {
        $sql = "SELECT posts.*, users.username, users.profile_image,
                   (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS likes
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = ?
                ORDER BY posts.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addComment($userId, $postId, $comment)
    {
        $stmt = $this->conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userId, $postId, $comment);
        $stmt->execute();
        $stmt->close();
    }

    public function getCommentById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateComment($id, $content)
    {
        $stmt = $this->conn->prepare("UPDATE comments SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteComment($commentId, $userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $commentId, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function getCommentsByPost($postId)
    {
        $stmt = $this->conn->prepare("
            SELECT comments.*, users.username, users.profile_image
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.post_id = ?
            ORDER BY comments.created_at ASC
        ");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePost($id, $content)
    {
        $stmt = $this->conn->prepare("UPDATE posts SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $id);
        $stmt->execute();
        $stmt->close();
    }
}

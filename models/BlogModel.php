<?php
require_once(__DIR__ . '/../config/db.php');

class BlogModel
{
    public function getAllBlogs()
    {
        global $conn;
        $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createBlog($title, $content, $author_id, $image, $publish_date)    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, image, publish_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $content, $author_id, $image, $publish_date);
        return $stmt->execute();
    }

    public function deleteBlog($id)
    {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

public function updateBlog($id, $title, $content, $image_path)
{
    global $conn;

    if ($image_path) {
        $stmt = $conn->prepare(
            "UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?"
        );
        $stmt->bind_param("sssi", $title, $content, $image_path, $id);
    } else {
        $stmt = $conn->prepare(
            "UPDATE posts SET title = ?, content = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $title, $content, $id);
    }

    return $stmt->execute();
}

}

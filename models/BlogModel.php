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

    public function createBlog($title, $content, $image)
    {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $image);
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
            "UPDATE blogs SET title = ?, content = ?, image_path = ? WHERE id = ?"
        );
        $stmt->bind_param("sssi", $title, $content, $image_path, $id);
    } else {
        $stmt = $conn->prepare(
            "UPDATE blogs SET title = ?, content = ? WHERE id = ?"
        );
        $stmt->bind_param("ssi", $title, $content, $id);
    }

    return $stmt->execute();
}

}

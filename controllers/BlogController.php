<?php
require_once 'C:\Apache24\htdocs\blog-management\models\BlogModel.php';

class BlogController
{
    private $model;

    public function __construct()
    {
        $this->model = new BlogModel();
    }

    public function fetchAllBlogs()
    {
        try {
            $blogs = $this->model->getAllBlogs();
            header("Content-Type: application/json");
            echo json_encode($blogs);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
    public function createBlog()
    {
        if (
            isset($_POST['title']) &&
            isset($_POST['content']) &&
            isset($_FILES['image'])
        ) {
            // Trim spaces from input
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
    
            // Validate title and content for invalid characters or patterns
            if (!preg_match("/^[a-zA-Z0-9 ]+$/", $title)) {
                http_response_code(400);
                echo json_encode(["message" => "Title contains invalid characters. Only alphanumerics and spaces allowed."]);
                exit();
            }
    
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $content)) {
                http_response_code(400);
                echo json_encode(["message" => "Content contains invalid characters. Only alphanumerics and spaces allowed."]);
                exit();
            }
    
            $image = $_FILES['image'];
    
            if (
                $image['error'] === UPLOAD_ERR_OK &&
                in_array($image['type'], ['image/jpeg', 'image/png', 'image/gif']) &&
                $image['size'] <= 5000000
            ) {
                $upload_dir = __DIR__ . '/../uploads/';
    
                // Ensure the directory exists with proper permissions and ownership
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                    chown($upload_dir, 'www-data');
                    chgrp($upload_dir, 'www-data');
                }
    
                // Create a unique file name
                $image_name = uniqid() . '-' . basename($image['name']);
                $image_path = $upload_dir . $image_name;
    
                // Move uploaded file
                if (move_uploaded_file($image['tmp_name'], $image_path)) {
                    chmod($image_path, 0644);
    
                    // Save only the image name to the database
                    $this->model->createBlog($title, $content, $image_name);
    
                    header("Content-Type: application/json");
                    echo json_encode(["message" => "Blog created successfully", "image" => $image_name]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Image upload failed"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Invalid file type or size"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields"]);
        }
    }
    
    public function deleteBlog($id)
    {
        try {
            if ($this->model->deleteBlog($id)) {
                header("Content-Type: application/json");
                echo json_encode(["message" => "Blog deleted successfully"]);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Unable to delete blog"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function updateBlog($id)
    {
        if (
            isset($_POST['title']) &&
            isset($_POST['content'])
        ) {
            // Sanitize and trim inputs
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
    
            // Validate title and content
            if (!preg_match("/^[a-zA-Z0-9 ]+$/", $title)) {
                http_response_code(400);
                echo json_encode(["message" => "Invalid title. Only alphanumerics and spaces are allowed."]);
                return;
            }
    
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $content)) {
                http_response_code(400);
                echo json_encode(["message" => "Invalid content. Only alphanumerics and spaces are allowed."]);
                return;
            }
    
            $image_path = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['image'];
                if (
                    in_array($image['type'], ['image/jpeg', 'image/png', 'image/gif']) &&
                    $image['size'] <= 5000000
                ) {
                    $upload_dir = __DIR__ . '/../../uploads/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
    
                    $image_path = $upload_dir . uniqid() . '-' . basename($image['name']);
    
                    if (!move_uploaded_file($image['tmp_name'], $image_path)) {
                        http_response_code(500);
                        echo json_encode(["message" => "Image upload failed"]);
                        return;
                    }
    
                    // Relative path to save in DB
                    $image_path = '/uploads/' . basename($image_path);
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Invalid image file"]);
                    return;
                }
            }
    
            // Call to Model for database update
            if ($this->model->updateBlog($id, $title, $content, $image_path)) {
                header("Content-Type: application/json");
                echo json_encode(["message" => "Blog updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Update failed"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields"]);
        }
    }
    
}
?>

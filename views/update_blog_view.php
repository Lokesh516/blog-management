<?php
require_once 'C:\\Apache24\\htdocs\\blog-management\\auth.php';
checkAdminAccess(); // Perform the admin access check
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <form id="updateForm">
        <input type="hidden" id="blogId" name="blogId">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required></textarea>
        <br>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image">
        <br>
        <button type="submit">Update Blog</button>
    </form>

    <script>
        document.getElementById('title').addEventListener('blur', function (e) {
        e.target.value = e.target.value.trim();
      });
       document.getElementById('content').addEventListener('blur', function (e) {
          e.target.value = e.target.value.trim();
       });
        // AJAX to handle form submission
        $("#updateForm").on("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const blogId = $("#blogId").val();

            $.ajax({
                url: `http://localhost/blog-management/api/blogs/${blogId}`,
                method: "PUT",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    alert(response.message);
                    window.location.href = "index.php"; // Redirect after update
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message);
                }
            });
        });
    </script>
</body>
</html>

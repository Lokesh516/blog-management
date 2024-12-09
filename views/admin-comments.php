<?php
require_once 'C:\\Apache24\\htdocs\\blog-management\\auth.php';
checkAdminAccess(); // Perform the admin access check
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment Moderation</title>
    <link rel="stylesheet" href="css/admin-comments.css">
</head>
<body>
    <div class="container">
        <h1>Comment Moderation Panel</h1>
        <div id="comments-section" class="comments-section"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            loadAllComments();

            function loadAllComments() {
                $.ajax({
                    url: 'http://localhost/blog-management/api/comments/index.php?action=viewAll',
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        renderComments(response);
                    },
                    error: function () {
                        alert('Failed to load comments.');
                    }
                });
            }

            function renderComments(comments) {
                let commentsHtml = '';
                comments.forEach(comment => {
                    commentsHtml += `
                        <div class="comment">
                            <p><strong>Post ID:</strong> ${comment.post_id}</p>
                            <p><strong>User:</strong> ${comment.user_name}</p>
                            <p><strong>Comment:</strong> ${comment.comment}</p>
                            <div class="actions">
                                <button class="delete-btn" onclick="deleteComment(${comment.id})">Delete</button>
                            </div>
                        </div>
                    `;
                });
                $('#comments-section').html(commentsHtml);
            }

            window.deleteComment = function (id) {
                if (confirm('Are you sure you want to delete this comment?')) {
                    $.ajax({
                        url: 'http://localhost/blog-management/api/comments/index.php?action=delete',
                        method: 'POST',
                        data: { action : 'delete', id: id },
                        success: function (response) {
                            if (response.success) {
                                alert('Comment deleted');
                                loadAllComments();
                            } else {
                                alert('Error deleting comment');
                            }
                        },
                        error: function () {
                            alert('Failed to delete the comment.');
                        }
                    });
                }
            };
        });
    </script>
</body>
</html>

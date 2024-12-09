<?php
require_once 'C:\\Apache24\\htdocs\\blog-management\\auth.php';
checkUserAccess(); // Perform the admin access check
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>
    <link rel="stylesheet" href="views\css\single-post.css">
</head>
<body>
    <div class="container">
        <div class="post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <img src="http://localhost/blog-management/uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Blog Image" style="width: 100px; height: auto;">
            <p><?php echo substr($post['content'], 0, 100); ?>...</p>  
            <p><strong>Author: </strong><?php echo htmlspecialchars($post['author_name']); ?></p>
            <p><strong>Publish Date: </strong><?php echo htmlspecialchars($post['publish_date']); ?></p>
        </div>

        <div id="comments-section">
            <h3>Comments</h3>
            <form id="comment-form">
                <textarea id="comment" placeholder="Your Comment" required></textarea>
                <button type="submit">Add Comment</button>
            </form>

            <div id="comments-list">
                <!-- Comments will be loaded here -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('comment').addEventListener('blur', function (e) {
          e.target.value = e.target.value.trim();
        });
        $(document).ready(function () {
            var postId = <?php echo $post['id']; ?>;
            loadComments(postId);

            $("#comment-form").submit(function (e) {
                e.preventDefault();
                var userName = $("#user_name").val();
                var commentText = $("#comment").val();
                $.ajax({
                    url: 'http://localhost/blog-management/api/comments.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ post_id: postId, comment: commentText }),
                    success: function (response) {
                        loadComments(postId);
                    },
                    error: function (response) {
                        alert(response.responseJSON.message);
                    }
                });
            });

            function loadComments(postId) {
                $.ajax({
                    url: 'http://localhost/blog-management/api/comments.php?post_id=' + postId,
                    method: 'GET',
                    dataType: "json",
                    success: function (response) {
                        var commentsHtml = '';
                        response.comments.forEach(function (comment) {
                            commentsHtml += '<div class="comment"><strong>' + comment.user_name + ':</strong> ' + comment.comment + '</div>';
                        });
                        $('#comments-list').html(commentsHtml);
                    }
                });
            }
        });
    </script>
</body>
</html>

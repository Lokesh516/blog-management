<?php
require_once 'C:\\Apache24\\htdocs\\blog-management\\auth.php';
checkUserAccess(); // Perform the admin access check
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search and Pagination</title>
  <link rel="stylesheet" href="views/css/home.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

  <button id="logout-btn">Logout</button>
  <!-- Search Section -->
  <form id="search-form" method="GET" action="index.php?controller=Post&action=showPosts">
    <input id="search-query" type="text" name="search" placeholder="Search by title or keywords" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    <button type="submit">Search</button>
  </form>

  <button type="button" id="clear-search" onclick="window.location.href = 'index.php?controller=Post&action=showPosts';">Clear Search</button>

  <!-- Posts List -->
  <div id="posts-list">
    <?php foreach ($posts as $post): ?>
      <div class="post" id="post-<?php echo $post['id']; ?>">
        <h3><?php echo $post['title']; ?></h3>
        <img src="http://localhost/blog-management/uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Blog Image" style="width: 100px; height: auto;">
        <p><?php echo substr($post['content'], 0, 100); ?>...</p>  
        <p><strong>Published on:</strong> <?php echo $post['publish_date']; ?></p>
        <a href="index.php?controller=Post&action=showPost&id=<?php echo $post['id']; ?>">Read more</a>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination Section -->
  <div class="pagination">
    <?php if ($currentPage > 1): ?>
      <a href="index.php?controller=Post&action=showPosts&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>&page=<?php echo $currentPage - 1; ?>">Previous</a>
    <?php endif; ?>

    <?php
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);

    for ($i = $startPage; $i <= $endPage; $i++): ?>
      <a href="index.php?controller=Post&action=showPosts&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>&page=<?php echo $i; ?>" 
         <?php if ($i == $currentPage) echo 'style="font-weight: bold;"'; ?>>
        <?php echo $i; ?>
      </a>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
      <a href="index.php?controller=Post&action=showPosts&search=<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>&page=<?php echo $currentPage + 1; ?>">Next</a>
    <?php endif; ?>
  </div>
  <script>
    $(document).ready(function () {

      // Handle the logout functionality
      $('#logout-btn').click(function () {
        $.ajax({
          url: '/blog-management/api/logout.php',
          method: 'POST',
          dataType: 'json',
          success: function(response) {
            if (response.status === "success") {
              alert(response.message);
              window.location.href = "/blog-management/views/login.php"; // Fixed path redirection
            } else {
              alert(response.message || "Logout failed.");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert(`Error: ${textStatus}, ${errorThrown}`);
            console.error("AJAX error details:", jqXHR.responseText);
          }
        });
      });  
    });
  </script>
</body>
</html>

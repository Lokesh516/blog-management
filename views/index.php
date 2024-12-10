<?php
require_once 'C:\Apache24\htdocs\blog-management\auth.php';
checkAdminAccess(); // Perform a general user access check
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Management</title>
    <link rel="stylesheet" href="css\index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Blog Management</h1>
    <div class="container">
        <!-- Blog Form -->
        <form id="blogForm" enctype="multipart/form-data">
            <h2 id="formTitle">Create Blog</h2>
            <input type="hidden" id="blogId" name="blogId">
            <label>Title: <input type="text" id="title" name="title" required></label>
            <label>Content: <textarea id="content" name="content" required></textarea></label>
            <label>Image: <input type="file" id="image" name="image"></label>
            <button type="submit" id="submitButton">Submit</button>
            <button type="button" id="cancelUpdate" style="display:none;" onclick="resetForm()">Cancel</button>
        </form>
    </div>

    <h2>All Blogs</h2>
    <div id="blogsContainer" class="container"></div>

    <script>
       function fetchBlogs() {
  $.ajax({
    url: "http://localhost/blog-management/api/blogs/index.php",
    method: "GET",
    success: function(data) {
      let htmlContent = "";
      data.forEach(blog => {
        let imageURL = `http://localhost/blog-management/uploads/${blog.image}`;
        console.log(imageURL);
        htmlContent += `<div class="blog-card">
                    <h3>${blog.title}</h3>
                    <p>${blog.content}</p>
                    <img src="${imageURL}" alt="Blog Image">
                    <div class="buttons">
                        <button onclick="deleteBlog(${blog.id})">Delete</button>
                        <button onclick="editBlog(${blog.id}, '${blog.title}', '${blog.content}')">Update</button>
                    </div>
                </div>`;
      });
      $("#blogsContainer").html(htmlContent);
    },
    error: function() {
      alert("Error fetching blogs");
    }
  });
}

$("#blogForm").on('submit', function(e) {
    e.preventDefault();

    // Trim title and content inputs
    const title = $("#title").val().trim();
    const content = $("#content").val().trim();

    // Validate title and content are not empty after trimming
    if (title === "" || content === "") {
        alert("Title and Content cannot be empty or contain only spaces.");
        return;
    }

    // Update the input fields to reflect trimmed values
    $("#title").val(title);
    $("#content").val(content);

    const blogId = $("#blogId").val();
    const formData = new FormData(this);

    const url = blogId
        ? `http://localhost/blog-management/api/blogs/index.php/update/${blogId}`
        : "http://localhost/blog-management/api/blogs/index.php";

    const method = blogId ? "POST" : "POST";

    $.ajax({
        url: url,
        method: method,
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response.message);
            fetchBlogs();
            resetForm();
        },
        error: function() {
            alert(blogId ? "Failed to update blog" : "Failed to create blog");
        }
    });
});


function deleteBlog(id) {
  $.ajax({
    url: `http://localhost/blog-management/api/blogs/index.php/delete/${id}`,
    method: "DELETE",
    success: function(response) {
      alert(response.message);
      fetchBlogs();
    },
    error: function() {
      alert("Failed to delete blog");
    }
  });
}

function editBlog(id, title, content) {
  // Pre-fill form with blog data for editing
  $("#formTitle").text("Update Blog");
  $("#submitButton").text("Update Blog");
  $("#cancelUpdate").show();
  $("#blogId").val(id);
  $("#title").val(title);
  $("#content").val(content);
  document.getElementById("formTitle").scrollIntoView({ behavior: "smooth" });
}

function resetForm() {
  // Reset form back to create mode
  $("#formTitle").text("Create Blog");
  $("#submitButton").text("Submit");
  $("#cancelUpdate").hide();
  $("#blogId").val("");
  $("#title").val("");
  $("#content").val("");
  $("#image").val("");
}

// Fetch blogs when page loads
fetchBlogs();
</script>
</body>
</html>

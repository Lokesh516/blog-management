<?php
require_once 'C:\\Apache24\\htdocs\\blog-management\\auth.php';
checkAdminAccess(); // Perform the admin access check
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css\admin-dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Style for logout button - positioned at the top-right corner */
        #logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 8px 12px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        #logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <!-- Logout button - Top Right -->
    <button type="button" id="logout-btn">Logout</button>

    <div class="container">
        <h1>Welcome, Admin!</h1>
        <div class="links">
            <a href="user_management.php" class="link">Manage Users</a>
            <a href="index.php" class="link">Manage Posts</a>
            <a href="admin-comments.php" class="link">Manage Comments</a>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Handle the logout functionality
            $('#logout-btn').click(function () {
                $.ajax({
                    url: 'http://localhost/blog-management/api/logout.php',
                    method: 'POST',
                    success: function(response) {
                        if (response.status === "success") {
                            alert(response.message);
                            window.location.href = "login.php";
                        } else {
                            alert("Logout failed.");
                        }
                    },
                    error: function() {
                        alert("An error occurred during logout.");
                    }
                });
            });
        });
    </script>

</body>
</html>

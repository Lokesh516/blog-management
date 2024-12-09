<?php
require_once 'C:\\Apache24\\htdocs\\blog-management\\auth.php';
checkAdminAccess(); // Perform the admin access check
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="css\user_management.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  
</head>
<body>
    <header>
        <h1>User Management Panel</h1>
    </header>

    <!-- Users Table Section -->
    <main>
        <div class="container">
            <table id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows will load here -->
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2023 Blog Management System</p>
    </footer>

    <!-- JavaScript Logic -->
    <script>
        $(document).ready(function () {
            /**
             * Fetch users from database on page load
             */
            function fetchUsers() {
                $.ajax({
                    url: "http://localhost/blog-management/api/users/index.php?action=getAllUsers",
                    method: "GET",
                    dataType: "json",
                    success: function (response) {
                        const usersTable = $("#usersTable tbody");
                        usersTable.empty(); // Clear old data before rendering new data
                        if (response?.success && Array.isArray(response.users)) {
                            response.users.forEach(user => {
                                const isActive = user.is_active === '1'; // Handle '1' as true
                                usersTable.append(`
                                    <tr>
                                        <td>${user.id}</td>
                                        <td>${user.username}</td>
                                        <td>${user.email}</td>
                                        <td>
                                            <button onclick="deleteUser(${user.id})">Delete</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            usersTable.append("<tr><td colspan='5'>No users available or invalid response.</td></tr>");
                        }
                    },
                    error: function () {
                        alert("Error while fetching user data.");
                    }
                });
            }


            /**
             * Handle user deletion
             */
            window.deleteUser = function (userId) {
                console.log( userId );
                if (confirm("Are you sure you want to delete this user?")) {
                    $.ajax({
                        url: `http://localhost/blog-management/api/users/index.php?action=deleteUser`,
                        method: "POST",
                        dataType: "json",
                        data: { "id" : userId },
                        success: function (response) {
                            console.log("Delete response: ", response);
                            alert(response.message);
                            fetchUsers();
                        },
                        error: function () {
                            alert("Failed to delete user.");
                        }
                    });
                }
            };

            // Fetch user data on initial page load
            fetchUsers();
        });
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css\registration.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="registration-container">
        <h1>Registration</h1>
        <form id="register-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username"  required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <!-- Role Selection with Radio Buttons -->
            <label>Role:</label>
            <div class="role-options">
                <input type="radio" id="user" name="role" value="0" required>
                <label for="user">User</label>
                <input type="radio" id="admin" name="role" value="1" required>
                <label for="admin">Admin</label>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {

            $('#username, #email, #password').on('input', function () {
                const value = $(this).val();
                $(this).val(value.trim());
            });

             $('#register-form').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission

                const username = $('#username').val()
                if (username.length < 3 || username.length > 20) {
                    if (username.length < 3) {
                        alert( "Username must be at least 3 characters.");
                        return; 
                } else if (username.length > 20) {
                        alert("Username must not exceed 20 characters.");
                        return; 
                } 

                // const usernameRegex = /^[A-Za-z]+$/;

                // if (!usernameRegex.test(username)) {
                //    alert("Username can only contain letters (no numbers or special characters).");
                //    return;
                //  }


            }

                const data = {
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    is_admin: $('input[name="role"]:checked').val(), // Get selected role (0 or 1)
                };

                // Send data to the backend API
                $.ajax({
                    url: '/blog-management/api/Register.php', // API endpoint
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function (response) {
                        alert(response.message);
                        window.location.href = 'login.php'; // Redirect to login on success
                    },
                    error: function (xhr) {
                        const errorResponse = JSON.parse(xhr.responseText);
                        alert(errorResponse.message || 'Error occurred during registration.');
                    },
                });
            });
        });
    </script>
</body>
</html>

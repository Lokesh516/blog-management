
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css\login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form id="login-form" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn">Login</button>
        </form>

        <button onclick="window.location.href='http://localhost/blog-management/views/forgot_password.php'" class="btn forgot-btn">
            Forgot Password?
        </button>
    </div>
    <script>
    $(document).ready(function () {

        $('#email, #password').on('input', function () {
                const value = $(this).val();
                $(this).val(value.trim());
            });

        $('#login-form').submit(function (e) {
            e.preventDefault(); // Prevent the default form submission
            
            // Get the email and password values
            var email = $('#email').val();
            var password = $('#password').val();

            // Send AJAX request to login API
            $.ajax({
                url: 'http://localhost/blog-management/api/login.php', 
                type: 'POST',
                data: JSON.stringify({ email: email, password: password }),
                contentType: 'application/json',
                success: function (response) {
                    // Check if the user is admin or regular user
                    if (response.is_admin === 1) {
                        // Redirect admins to admin dashboard
                        window.location.href = '/blog-management/views/admin-dashboard.php';
                    } else {
                        console.log("not working")
                        // Redirect regular users to user dashboard
                        window.location.href = '/blog-management/index.php';
                    }
                },
                error: function (error) {
                    // Handle login failure
                    alert('Login failed: ' + error.responseJSON.message);
                }
            });
        });
    });
</script>

</body>
</html>

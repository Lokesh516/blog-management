<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/forgot_password.css">
</head>
<body>
    <form id="forgot-password-form">
        <h2>Forgot Password</h2>
        <label>Email:
            <input type="email" id="email" required>
        </label>
        <button type="submit">Submit</button>
        <div id="response-message"></div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#email').on('input', function () {
                const value = $(this).val();
                $(this).val(value.trim());
            });

            $('#forgot-password-form').submit(function (e) {
                e.preventDefault();
                const email = $('#email').val();

                $.ajax({
                    url: 'http://localhost/blog-management/api/forgot_password.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ email: email }),
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#response-message').css('color', 'green').text(response.message);
                        } else {
                            $('#response-message').css('color', 'red').text(response.message);
                        }
                    },
                    error: function () {
                        alert('An error occurred. Please try again later.');
                    }
                });
            });
        });
    </script>
</body>
</html>

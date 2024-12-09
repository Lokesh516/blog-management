<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css\reset_password.css">
</head>
<body>
    <form id="reset-password-form">
        <h2>Reset Password</h2>
        <label>Enter New Password:
            <input type="password" id="password" required>
        </label>
        <button type="submit">Submit</button>
        <div id="response-message"></div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            $('#password').on('input', function () {
                const value = $(this).val();
                $(this).val(value.trim());
            });

            $('#reset-password-form').submit(function (e) {
                e.preventDefault();
                const newPassword = $('#password').val();
                console.log(token);

            $.ajax({
            url: 'http://localhost/blog-management/api/reset_password.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ token: token, password: newPassword }),
            success: function (response) {
                 $('#response-message').css('color', response.status === 'success' ? 'green' : 'red')
                                  .text(response.message);

                // Redirect to the login page after success
                 if (response.status === 'success') {
                        setTimeout(() => {
                             window.location.href = 'login.php';
                        }, 1500); // Redirect after 1.5 seconds to allow user to see the success message
                 }
            },
            error: function (jqXHR) {
                 try {
                const response = JSON.parse(jqXHR.responseText);
                      $('#response-message').css('color', 'red').text(response.message);
                 } catch (error) {
                     console.error('Error parsing server response', error);
                     alert('Unexpected server response.');
            }
        }
     });
});
        });
    </script>
</body>
</html>

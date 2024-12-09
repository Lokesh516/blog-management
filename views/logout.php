<button id="logout-button">Logout</button>

<script>
$('#logout-button').click(function () {
    $.ajax({
        url: '/api/logout',
        method: 'POST',
        success: function (response) {
            alert(response.message);
            window.location.href = 'login.php'; // Redirect to login page
        },
        error: function () {
            alert('Error during logout.');
        },
    });
});
</script>

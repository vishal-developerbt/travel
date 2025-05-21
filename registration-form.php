<form id="user-registration-form">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control" id="firstName" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control" id="lastName" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="registerEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="Username" class="form-label">Username</label>
                            <input type="username" name="username" class="form-control" id="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="registerPassword" required>
                        </div>
                        <button type="submit" class="btn btn-success">Register</button>
                    </form>

<script>
jQuery(document).ready(function($) {
    $('#user-registration-form').submit(function(e) {
        e.preventDefault();
        var formData = {
            action: 'custom_user_registration',
            firstname: $('#firstname').val(),
            lastname: $('#lastname').val(),
            email: $('#email').val(),
            username: $('#username').val(),
            password: $('#password').val(),
        };

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', formData, function(response) {
            $('#register-message').html(response);
        });
    });
});
</script>
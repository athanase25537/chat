<?php
session_start();

$title = "Chap App - Login";
require "src/elements/header.php";

if(!isset($_SESSION['username'])):


?>
    <div class="w-400 p-5 shadow rounded">
        <form method="post"
              action="app/http/auth.php">
            <div class="d-flex
                        justify-content-center
                        align-items-center
                        flex-column">
                <img src="src/img/un-message.png" alt="logo"
                     class="w-25">
                <h3 class="display-4 fs-1 text-center">
                    LOGIN
                </h3>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-warning">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif ?>

            <div class="mb-3">
                <label class="form-label">
                    User name</label>
                <input type="text"
                       class="form-control"
                       name="username">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password</label>
                <input type="password"
                       class="form-control"
                       name="password">
            </div>
            <button type="submit"
                    class="btn btn-primary">
                    Login</button>
            <a href="signup.php">Sign Up</a>
        </form>
    </div>
</body>
</html>

<?php else:
    header("Location: home.php");
    exit;
endif ?>
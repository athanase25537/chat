<?php
session_start();
if(!isset($_SESSION['username'])):
$name = isset($_GET['name']) ? $_GET['name'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chap App - Sing Up</title>
    <link rel="stylesheet" href="src/css/bootstrap.min.css">
    <link rel="stylesheet" href="src/css/style.css">
</head>
<body class="d-flex
             justify-content-center
             align-items-center
             vh-100">
    <div class="w-400 p-5 shadow rounded">
        <form method="post"
              action="app/http/signup.php"
              enctype="multipart/form-data">
            <div class="d-flex
                        justify-content-center
                        align-items-center
                        flex-column">
                <img src="src/img/un-message.png" alt="logo"
                     class="w-25">
                <h3 class="display-4 fs-1 text-center">
                    Sign Up
                </h3>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-warning">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif ?>

            <div class="mb-3">
                <label class="form-label">
                    Name</label>
                <input type="text"
                       class="form-control"
                       name="name"
                       value="<?= $name ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    User name</label>
                <input type="text"
                       class="form-control"
                       name="username"
                       value="<?= $username ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password</label>
                <input type="password"
                       class="form-control"
                       name="password">
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Profile Picture</label>
                <input type="file"
                       class="form-control"
                       name="pp">
            </div>

            <button type="submit"
                    class="btn btn-primary">
                    Sign Up</button>
            <a href="index.php">Login</a>
        </form>
    </div>
</body>
</html>

<?php else:
    header("Location: home.php");
    exit;
endif ?>
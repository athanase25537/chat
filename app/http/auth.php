<?php
session_start();
// check if username, password submitted
if(isset($_POST['username']) &&
    isset($_POST['password'])
){

    // database connection file
    require '../db-connection.php';

    // get data from POST request and store them in var
    $password = $_POST['password'];
    $username = $_POST['username'];

    // simple form validation
    if(empty($username)){
        // error message
        $em = "Username is required";
        $em = urlencode($em);

        /*
            redirect to 'index.php' and 
            passign error message
        */
        header("Location: ../../index.php?error=$em");
        exit;
    }else if(empty($password)){
        // error message
        $em = "Password is required";
        $em = urlencode($em);

        /*
            redirect to 'index.php' and 
            passign error message
        */
        header("Location: ../../index.php?error=$em");
        exit;
    }else{
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $username]);

        // if the username exist
        if($stmt->rowCount() === 1){
            // fetching user data
            $user = $stmt->fetch();

            // if both username's are strictly equal
            if($user['username' === $username]){
                // verifying the encrypted password
                if(password_verify($password, $user['password'])){
                    // successfully logged in
                    // creating the SESSION
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['user_id'] = $user['user_id'];

                    // redirect to 'home.php'
                    header("Location: ../../home.php");
                    exit;
                }else{
                    // error message
                    $em = "Incorrect username or password";
                    $em = urlencode($em);

                    /*
                        redirect to 'index.php' and 
                        passign error message
                    */
                    header("Location: ../../index.php?error=$em");
                    exit;
                }
            }else{
                // error message
                $em = "Incorrect username or password";
                $em = urlencode($em);
    
                /*
                    redirect to 'index.php' and 
                    passign error message
                */
                header("Location: ../../index.php?error=$em");
                exit;
            }
        }else{
            // error message
            $em = "Incorrect username or password";
            $em = urlencode($em);

            /*
                redirect to 'index.php' and 
                passign error message
            */
            header("Location: ../../index.php?error=$em");
            exit;
        }
    }
}else{
    header("Location: ../../index.php");
    exit;
}

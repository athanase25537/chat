<?php
// check if username, password, name submitted

if(isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['name'])
){

    // database connection file
    require '../db-connection.php';

    // get data from POST request and store them in var
    $name = $_POST['name'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    // making URL data format
    $data = 'name=' . $name . '&username=' . $username;

    // simple form Validation
    if(empty($name)){
        // error message
        $em = "Name is required";
        $em = urlencode($em);

        /*
            redirect to 'signup.php' and 
            passign error message and data
        */
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    }else if(empty($username)){
        // error message
        $em = "Username is required";
        $em = urlencode($em);

        /*
            redirect to 'signup.php' and 
            passign error message and data
        */
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    }else if(empty($password)){
        // error message
        $em = "Password is required";

        $em = urlencode($em);
        /*
            redirect to 'signup.php' and 
            passign error message and data
        */
        header("Location: ../../signup.php?error=$em&$data");
        exit;
    }else{
        // checking the database if the username is taken
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        if($stmt->rowCount() > 0){
            $em = "The username ($username) is taken";
            header("Location: ../../signup.php?error=$em&$data");
            exit;
        }else{
            // Profile Picture Uploading
            if(isset($_FILES['pp'])){
                // get data and store them in var
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                // if there is not error occurred while uploading
                if($error == 0){
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION); 
                    
                    /** 
                     * Convert the image extension into lowercase
                     * and store it in var
                    */
                    $img_ex_lc = strtolower($img_ex);

                    /**
                     * Creation array that stores allowed
                     * to upload image extenstion.
                     */
                    $allowed_exs = array("jpg", "jpeg", "png");

                    /**
                     * check if the image extension
                     * is present in $allowed_exs array
                     */
                    if(in_array($img_ex_lc, $allowed_exs)){
                        /**
                           * renaming the image with user's username
                         * like: username.$img_ex_lc
                         */
                        $new_img_name = $username . '.' . $img_ex_lc;

                        //  Creating upload on root directory
                        $img_upload_path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $new_img_name;
                        // $img_upload_path = "../../uploads/$new_img_name";

                        // move uploaded image to ./upload folder
                        move_uploaded_file($tmp_name, $img_upload_path);
                    }else{
                        $em = "You can't upload filex of this type";
                        header("Location: ../../signup.php?error=?$em&data");
                        exit;
                    }
                }
            }

            // password hashing
            $password = password_hash($password, PASSWORD_DEFAULT);

            // if the user upload Profile Picture
            if(isset($new_img_name)){
                // inserting data into database
                $sql = "INSERT INTO users
                        (name, username, password, p_p)
                        VALUES (:name, :username, :password, :p_p)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'name' => $name,
                    'username' => $username,
                    'password' => $password,
                    'p_p' => $new_img_name
                ]);

            }else{
                // inserting data into database
                $sql = "INSERT INTO users
                        (name, username, password)
                        VALUES (:name, :username, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'name' => $name,
                    'username' => $username,
                    'password' => $password
                ]);
            }

            // succes message
            $sm = "Accound created successfully";

            // redirect to 'index.php' and passing success message
            header("Location: ../../index.php?success=$sm");
            exit;
        }
    }
}else{
    header("Location: ../../signup.php");
    exit;
}
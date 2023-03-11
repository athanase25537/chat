<?php
session_start();
require "vendor/autoload.php"
?>
<?php
if(isset($_SESSION['username'])):
require "app/db-connection.php";

require "app/helpers/user.php";
require "app/helpers/conversations.php";
require "app/helpers/timeAgo.php";
require "app/helpers/last_chat.php";

// getting user data
$user = getUser($_SESSION['username'], $conn);

// getting user conversations
$conversations = getConversation($user['user_id'], $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App - Home</title>

    <link rel="stylesheet" href="src/css/bootstrap.min.css">
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="shortcut icon" href="src/img/un-message.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="d-flex
            justify-content-center
            align-items-center
            vh-100">
    <div class="p-2
                w-400
                rounded shadow">
        <div>
            <div class="d-flex
                mb-3 p-3 bg-light
                justify-content-between
                align-items-center">
                <div class="d-flex
                            align-items-center">
                    <img src="uploads/<?= $user['p_p'] ?>" alt="profile picture"
                        class="w-25 rounded-circle">
                    <h3 class="fs-xs"><?= $user['name'] ?></h3>
                </div>
                <a href="logout.php"
                    class="btn btn-dark">Logout</a>
            </div>

            <div class="input-group mb-3">
                <input type="text" 
                        placeholder="Search..."
                        id="searchText"
                        class="form-control">
                <button class="btn btn-primary"
                        id="searchBtn">
                    <i class="fa fa-search"></i>
                </button>
            </div>
            
            <ul id="chatList" class="list-group mvh-50 overflow-auto">
                <?php if(!empty($conversations)): ?>
                    <?php foreach($conversations as $conversation): ?>
                        <li class="list-group-item">
                            <a href="chat.php?user=<?= $conversation['username'] ?>"
                                class="d-flex
                                        justify-content-between
                                        align-items-center
                                        p-2">
                                <div class="d-flex
                                            align-items-center">
                                    <img src="uploads/<?= $conversation['p_p'] ?>"
                                        alt=""
                                        class="w-10 rounded-circle">
                                    <h3 class="fs-xs m-2"><?= $conversation['name'] ?><br>
                                        <small>
                                            <?= lastChat($_SESSION['user_id'], $conversation['user_id'], $conn) ?>
                                        </small>
                                    </h3>
                                </div>
                                <?php if(last_seen($conversation['last_seen']) == "Active"): ?>
                                    <div title="online">
                                        <div class="online">

                                        </div>
                                    </div>
                                <?php endif ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="fa fa-comments d-block fs-big"></i>
                        No messages yet, Start the conversation
                    </div>
                <?php endif ?>
            </ul>
        </div>
    </div>


    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        $(document).ready(function(){


        // Search
        $("#searchText").on("input", function(){
            var searchText = $(this).val();
            if(searchText == "") return;
            $.post("app/ajax/search.php",
                    {
                        key: searchText
                    },
                    function(data, status){
                        $("#chatList").html(data);
                    });
        });

        // Search using the button
        $("#searchBtn").on("click", function(){
            var searchText = $("#searchText").val();
            if(searchText == "") return;
            $.post("app/ajax/search.php",
                    {
                        key: searchText
                    },
                    function(data, status){
                        $("#chatList").html(data);
                    });
        });

        /**
         * auto update last seen
         * for logged in user
         */
        let lastSeenUpdate = function(){
            $.get("app/ajax/update_last_seen.php");
        };
        lastSeenUpdate();
        /**
         * auto update last seen every 10 sec
         */
        setInterval(lastSeenUpdate, 10000);
    });
    </script>
</body>
</html>
<?php else:
    header("Location: index.php");
    exit;
endif ?>
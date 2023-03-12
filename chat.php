<?php
session_start();
require "vendor/autoload.php";

if(isset($_SESSION['username'])):
require "app/db-connection.php";

if(!isset($_GET['user'])){
    header("Location: home.php");
    exit;
}

require "app/helpers/user.php";
require "app/helpers/chat.php";
require "app/helpers/opened.php";


require "app/helpers/timeAgo.php";

// getting user data
$chatWith = getUser($_GET['user'], $conn);
if(empty($chatWith)){
    header("Location: home.php");
    exit;
}

$chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);
opened($chatWith['user_id'], $conn, $chats);

$title = "Chat App";
require "src/elements/header.php";
?>

    <div class="w-400 shadow p-4 rounded">
        <a href="home.php"
            class="fs-4 link-dark">&#8592;</a>
        <div class="d-flex align-items-center">
            <img src="uploads/<?= $chatWith['p_p'] ?>" alt="profile picture"
                class="w-15 rounded-circle">
            <h3 class="display-4 fs-sm m-2">
                <?= $chatWith['name'] ?> <br>
                <div class="d-flex
                    align-items-center"
                    title="online">
                    <?php if(last_seen($chatWith['last_seen']) == "Active"): ?>
                        <div class="online"></div>
                        <small class="d-block p-1">Online</small>
                    <?php else: ?>
                        <small class="d-block p-1">
                            Last seen: 
                            <?= last_seen($chatWith['last_seen']) ?>
                        </small>
                    <?php endif ?>
                </div>
            </h3>
        </div>

        <div class="shadow p-4 rounded
                d-flex flex-column
                mt-2 chat-box"
            id="chatBox">
            <?php if(!empty($chats)):
                foreach($chats as $chat):
                    if($chat['from_id'] == $_SESSION['user_id']):?>
                        <p class="rtext align-self-end
                                    border rounded p-2 mb-1">
                            <?= $chat['message'] ?>
                            <small class="d-block"><?= $chat['created_at'] ?></small>
                        </p>
                    <?php else: ?>
                        <p class="ltext
                        border rounded p-2 mb-1">
                            <?= $chat['message'] ?>
                            <small class="d-block"><?= $chat['created_at'] ?></small>
                        </p>
                    <?php endif ?>

                    
                <?php endforeach ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fa fa-comments d-block fs-big"></i>
                    No messages yet, Start the conversation
                </div>
            <?php endif ?>
        </div>
        <div class="input-group mb-3">
            <textarea name="" cols="3"
                id="message"
                class="form-control"></textarea>
            <button class="btn btn-primary"
                    id="sendBtn">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        var scrollDown = function(){
            let chatBox = document.getElementById("chatBox");
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        scrollDown();
        $(document).ready(function(){
            $("#sendBtn").on('click', function(){
                message = $("#message").val();
                if(message === "") return;

                $.post("app/ajax/insert.php",
                {
                    message: message,
                    to_id: <?= $chatWith['user_id'] ?>
                },
                function(data, status){
                    $("#message").val("");
                    $("#chatBox").append(data);
                    scrollDown();
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

            // auto refresh / reload
            let fechData = function(){
                $.post("app/ajax/getMessage.php",
                    {
                        id_2: <?= $chatWith['user_id'] ?>
                    },
                    function(data, status){
                    $("#chatBox").append(data);
                    if(data != "") scrollDown();
                });
            }
            fechData();
            /**
             * auto update last seen every 5 sec
             */
            setInterval(fechData, 500);


        });
    </script>
</body>
<?php else:
    header("Location: index.php");
    exit;
endif ?>
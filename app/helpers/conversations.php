<?php
function getConversation(int $user_id, PDO $conn){
    /**
     * getting all the conversations
     * for current (logged in) user
     */
    $sql = "SELECT * FROM conversations
                WHERE user_1 = :user_id OR user_2 = :user_id
                ORDER BY conversation_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
    "user_id" => $user_id
    ]);

    if($stmt->rowCount() > 0){
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /**
         * creationg empty array to
         * store the user conversation
         */
        $user_data = [];
        // looping through the conversations
        foreach($conversations as $conversation){
            // if conversations user_1 row equal to user_id
            if($conversation['user_1'] == $user_id){
                $sql2 = "SELECT name, username, p_p, last_seen, user_id
                        FROM users WHERE user_id = :user_id";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute(["user_id" => $conversation['user_2']]);
            }else{
                $sql2 = "SELECT name, username, p_p, last_seen, user_id
                FROM users WHERE user_id = :user_id";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute(["user_id" => $conversation['user_1']]);
            }
            $allConversations = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            // pushing data into the array
            array_push($user_data, $allConversations[0]);
        }
        return $user_data;
    }else{
    $conversations = [];
    }
}
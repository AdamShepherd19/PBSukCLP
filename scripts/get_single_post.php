<?php
    $thread_id = $_GET['threadIDPHP'];

    // https://makitweb.com/return-json-response-ajax-using-jquery-php
    $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

    //perform query and sort into newest first
    $sql = "SELECT * FROM threads WHERE thread_id=? LIMIT 1";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute([$thread_id]);
    $result = $stmt->fetchAll();


    if ($result){
        //initialise array
        $data = array();

        // output data of each row
        foreach($result as $row) {
            $sql = "SELECT firstname, lastname FROM users WHERE user_id=? LIMIT 1";
            $stmt = $connectionPDO->prepare($sql);
            $stmt->execute([$row['user_id']]);
            $names = $stmt->fetchAll();

            //retrieve data from query
            $title = $row['title'];
            $content = $row['content'];
            $approved = $row['approved'];
            $feedback_provided = $row['feedback_provided'];
            $firstname = $names[0]['firstname'];
            $lastname = $names[0]['lastname'];
            
            
            //add data into array
            $data[] = array(
                "title" => $title,
                "content" => $content,
                "approved" => $approved,
                "firstname" => $firstname,
                "lastname" => $lastname,
                "feedback_provided" => $feedback_provided
            );
        }
        //encode the array into jason
        echo json_encode($data);
    } else {
        echo json_encode("*warning_no_post_found*");
    }


    // close connection to db
    $stmt = null;
    $connectionPDO = null;
?>
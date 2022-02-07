<?php
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
    $sql = "SELECT * FROM `announcements` ORDER BY announcement_id DESC";
    $stmt = $connectionPDO->prepare($sql);
    $stmt->execute();

    //check that there were announcements to show
    if ($stmt->num_rows > 0) {

        //initialise array
        $data = array();

        // output data of each row
        while($row = $stmt->fetch()) {
            //retrieve data from query
            $id = $row['announcement_id'];
            $title = $row['title'];
            $content = $row['content'];
            $author = $row['author'];
            
            //add data into array
            $data[] = array(
                "id" => $id,
                "title" => $title,
                "content" => $content,
                "author" => $author
            );
        }

        //encode the array into jason
        echo json_encode($data);

    } else {
        echo json_encode("*warning_no_announcements_found*");
    }

    $connection->close();

?>
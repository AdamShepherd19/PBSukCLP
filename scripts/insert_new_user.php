<?php
    $pass = file_get_contents('../../pass.txt', true);
    
    if(isset($_POST['firstnamePHP'])) {
        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        //retrieve title, content and author for the new post
        $firstname = $_POST['firstnamePHP'];
        $lastname = $_POST['lastnamePHP'];
        $email = $_POST['emailPHP'];
        $contact_number = $_POST['contact_numberPHP'];
        $organisation = $_POST['organisationPHP'];
        $account_type = $_POST['account_typePHP'];
        

        // query database and insert the new announcement into the announcements table
        $sql = "INSERT INTO users (firstname, lastname, email, contact_number, organisation, account_type) VALUES (:firstname, :lastname, :email, :contact_number, :organisation, :account_type);";
        $stmt = $connectionPDO->prepare($sql);
        
        //check to see if the insert was successful
        if ($stmt->execute(['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'contact_number' => $contact_number, 'organisation' => $organisation, 'account_type' => $account_type])) {
            exit('*user_added_successfully*');
        } else {
            exit('Error: ' . $connectionPDO->error);
        }

        $stmt = null;
        $connectionPDO = null;

    }

?>
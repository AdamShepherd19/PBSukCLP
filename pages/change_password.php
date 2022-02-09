<?php
    session_start();

    // if already logged in
    if (isset($_SESSION['logged_in'])){
        header('Location: https://pbsclp.info');
        exit();
    }

    if($_GET['key'] && $_GET['token']) {
        $pass = file_get_contents('../../pass.txt', true);

        //connect to database
        try {
            $connectionPDO = new PDO('mysql:host=localhost;dbname=pbsclp_pbsclp', 'pbsclp', $pass);
            $connectionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            exit('*database_connection_error*');
        }

        $email = $_GET['key'];
        $token = $_GET['token'];
        $curDate = date("Y-m-d H:i:s");

        //fix query
        $sql = "SELECT * FROM users WHERE reset_link_token=:token and email=:email;";
        $stmt = $connectionPDO->prepare($sql);
        $stmt->execute(['token' => $token, 'email' => $email]);
        $data = $stmt->fetch();
        
        if ($data) {
            if($data['exp_date'] < $curDate){
                header('Location: https://pbsclp.info/pages/reset_password_expired.php');
            }
        }

        $stmt = null;
        $connectionPDO = null;

    } else{
        header('Location: https://pbsclp.info');
        exit();
    }

?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">

        <!-- bootstrap metadata -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <!-- Bootstrap javascript include links -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- include jQuery -->
        <script src="../includes/jquery.js"></script>

        <link rel="stylesheet" href="../stylesheets/style.css">
        <link rel="stylesheet" href="../stylesheets/login.css">

        <title>Change Password</title>
        
    </head>

    <body>

        <div class="logo-banner">
            <img src="../images/pbslogo.png" alt="PBSuk Logo">
        </div>

        <div class="login-container">

            <div class="login-header">
                <h1>PBSuk Collaboration and Learning Hub</h1>
            </div>

            <div class="login-form-container" id="form-container">
                <h2>Change Password</h2>
                <p>Please enter and confirm your new password:</p>

                <form>
                    <label for="new-password"></label>
                    <input type="text" id="new-password" class="pbs-form-text-box" placeholder="Enter password..."> <br/>

                    <label for="confirm-password"></label>
                    <input type="text" id="new-password" class="pbs-form-text-box" placeholder="Confirm password..."> <br/>

                    <input type="button" id="submit-change-password" class="pbs-button pbs-button-white" value="Confirm"> <br />
                </form>
            </div>
        </div>
    </body>
    


    <script type="text/javascript">
        $(document).ready(function () {
            
        });
    </script>
    
</html>
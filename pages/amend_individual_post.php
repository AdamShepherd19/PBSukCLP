<?php
    session_start();

    if(!isset($_SESSION['logged_in'])){
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
        <link rel="stylesheet" href="../stylesheets/forum_post.css">

        <title>Amend Post</title>
        
    </head>

    <body>

        <div id="pbs-nav-bar">
            <?php
                include "../common/nav-bar.php";
            ?>
        </div>

        <div class="page-header">
            <h1>Amend Post</h1>
        </div>

        <div class="main-content">

            <div id='post-section'>
                <!-- post here -->
            </div>

            <div id='feedback-section'>
                <h4>Feedback:</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text" id="feedback-text"></p>
                    </div>
                </div>
            </div>

            <div id="post-amendmend-section">
                <br>
                <h4>Amend Post:</h4>
                <br>
                <div class="form-wrapper">
                    <form>
                        <label for="amend-title">Title: </label><br />
                        <input type="text" id="amend-title" class="pbs-form-text-box" placeholder="Enter post title..."><br /><br />
                        <label for="amend-content">Content: </label><br />
                        <textarea id="amend-content" class="pbs-form-text-box text-area-large" placeholder="Enter post content..."></textarea><br />
                    </form>
                </div>
            </div>

            <div class="button-wrapper">
                <input type="button" id="amend-post-cancel" class="pbs-button pbs-button-red" value="Cancel"> 
                <input type="button" id="amend-post-submit" class="pbs-button pbs-button-green" value="Approve">
            </div>

            <br>
        </div>

        
        <script type="text/javascript">
            $(document).ready(function () {

                // only show administrator content if an admin logged in
                var accountType = '<?php echo $_SESSION['account_type']; ?>';
                if (accountType != 'administrator') {
                    $('.admin-only').hide();
                } else {
                    $('.admin-only').show();
                }

                var thread_id = "<?php echo $_GET['threadId']; ?>";

                $.ajax({
                    url: '../scripts/get_single_post.php',
                    type: 'get',
                    dataType: 'JSON',
                    data: {
                        threadIDPHP: thread_id
                    },
                    success: function(response) {
                        if (response.includes("*warning_no_post_found*")) {
                            var announcement = "<br><h2>This post does not exist.</h2>";

                            $('#post-section').html(announcement);
                            $("#review-post-submit").hide();
                            $("#feedback-section").hide();
                            $("#post-amendmend-section").hide();
                        } else {
                            if (response[0].approved == '0'){
                                var post = '<div class="forum-post card" id="thread-id-' + response[0].thread_id + '">' +
                                    '<div class="card-header">' + response[0].title + '<br><span class="post-name"><i> - ' + response[0].firstname + ' ' + response[0].lastname + '</i></span>' + '</div>' +
                                    '<div class="card-body">' +
                                        '<p>' + response[0].content + '</p>' +
                                    '</div></div><br>';

                                $('#amend-title').val(response[0].title);
                                $('#amend-content').text(response[0].content);

                                $('#post-section').html(post);

                                $.ajax({
                                    url: '../scripts/get_feedback.php',
                                    type: 'get',
                                    dataType: 'text',
                                    data: {
                                        threadIDPHP: thread_id
                                    },
                                    success: function(response) {
                                        if (response.includes("*warning_no_feedback_found*")) {
                                            $("#feedback-text").text("There is no current feedback for this post.");
                                        } else {
                                            $("#feedback-text").text(response);
                                        }
                                    }
                                });
                            } else {
                                $('#post-section').html("<br><h2>Warning: This post has already been approved.</h2>");
                                $("#feedback-section").hide();
                                $("#post-amendmend-section").hide();
                                $("#review-post-submit").hide();
                            }
                        }
                    }
                });

                // $('#name').text(name);
                // $('#email-address').text(email);

                $("#amend-post-cancel").on('click', function(){
                    window.location.replace('amend_posts.php');
                });

                // onclick function for the post announcement button
                $("#amend-post-submit").on('click', function(){
                    // //retrieve data from form
                    var new_title = $("#amend-title").val();
                    var new_content = $('#amend-content').val();

                    // //check data not empty
                    if(new_title == "" || new_content == ""){
                        //prompt user to fill in all data
                        alert("Please fill out the information in the form");
                    } else {
                        //send data to php
                        $.ajax({
                            method: 'POST',
                            url: "../scripts/update_forum_post.php",
                            data: {
                                new_titlePHP: new_title,
                                new_contentPHP: new_content,
                                thread_idPHP: thread_id
                            },
                            success: function (response) {
                                //check if the php execution was successful and the data was added to the db
                                if (response.includes("*post_updated_successfully*")){
                                    //replace html with success message and button to return to landing page
                                    var successHTML = "<h3>Your post was submitted succesfully. Please allow X days for the post to be reviewed and/or published. Click the button below to return to the landing page.</h3><br> " +
                                        "<input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>";

                                    $('.main-content').html(successHTML);

                                } else {
                                    //display error message if the php could not be executed
                                    $('.main-content').html("<h3> There was an error processing your request. Please try again </h3><br>Error" + response +
                                        "<br><input type='button' id='return' class='pbs-button pbs-button-green' value='Confirm'>");
                                }

                                // onclick function for new button to return to landing page
                                $("#return").on('click', function(){
                                    window.location.replace('landing.php');
                                });
                            },
                            datatype: 'text'
                        });
                    };
                });
            });
        </script>
        
    </body>
</html>
<?php
session_start();
include('db_config.php');
?>

<!DOCTYPE html>
<html>
    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Database</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="css/bulma.min.css" />
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>

<body>
    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <figure class="image is-rounded">
                    <a href="index.php"><img class="is-rounded" src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Deus_Books.png/1200px-Deus_Books.png"></a>
                    </figure>
                    <?php
                    if(isset($_SESSION['not_auth'])):
                    ?>
                        <div class="notification is-danger">
                        <p>ERROR: Wrong username or password!</p>
                        </div>
                    <?php
                    endif;
                    unset($_SESSION['not_auth']);
                    ?>
                    <div class="box">
                        <form action="login.php" method="POST">
                            <div class="field">
                                <div class="control">
                                    <input name="username" name="text" class="input is-large" placeholder="Username" autofocus="">
                                </div>
                            </div>

                             <div class="field">
                                <div class="control">
                                    <input name="password" class="input is-large" type="password" placeholder="Password">
                                </div>
                            </div>
                            <button type="submit" class="button is-block is-primary is-large is-fullwidth">Log In</button>
                        </form>
                        <div class="content is-medium">
                            <u><a href="register.php">No account yet?</a></u>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>

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
    <!-- NAVBAR -->
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
        <figure class="image is-96x96">
          <a href="main.php"><img class="is-rounded" src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Deus_Books.png/1200px-Deus_Books.png"></a>
        </figure>
        </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start content is-medium">
      <a class="navbar-item" href="main.php">
        Home
      </a>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Settings
        </a>

        <div class="navbar-dropdown content is-medium">
          <form action="change_pass.php" method="POST">
            <button><a class="navbar-item">
              Change Password
            </a></button>
          </form>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <form action="logout.php" method="POST">
            <button type="submit" class="button is-warning is-outlined is-medium">Log Out</button>
        </form> 
      </div>
    </div>
  </div>
</nav>


  <section class="hero is-success is-fullheight">
      <div class="hero-body">
          <div class="container has-text-centered">
              <div class="column is-4 is-offset-4">
                  <?php
                  if($_SESSION['password_weak']):
                  ?>
                  <div class="notification is-warning">
                        <p>WARNING: Weak password!</p>
                        <h6>Note: It must contain at least:</h6>
                        <h6> 8 digits;</h6>
                        <h6>1 uppercase and lowercase letter;</h6>
                        <h6>1 number;</h6>
                        <h6>1 special character;</h6>
                    </div>
                  <?php
                  endif;
                  unset($_SESSION['password_weak']);
                  ?>
                  <?php
                  if($_SESSION['nomatch_password']):
                  ?>
                  <div class="notification is-danger">
                      <p>ERROR: Passwords don't match!</p>
                  </div>
                  <?php
                  endif;
                  unset($_SESSION['nomatch_password']);
                  ?>
                  <?php
                  if($_SESSION['wrong_oldpass']):
                  ?>
                  <div class="notification is-danger">
                      <p>ERROR: Old password doesn't match!</p>
                  </div>
                  <?php
                  endif;
                  unset($_SESSION['wrong_oldpass']);
                  ?>
                  <?php
                  if($_SESSION['password_changed']):
                  ?>
                  <div class="notification is-success">
                      <p>SUCCESS: Password changed!</p>
                  </div>
                  <?php
                  endif;
                  unset($_SESSION['password_changed']);
                  ?>
                  <div class="box">
                      <form action="change_pass.php" method="POST">
                          <div class="field">
                              <div class="control">
                                  <input name="oldpassword" class="input is-large" type="password" placeholder="Old Password">
                              </div>
                          </div>
                          <div class="field">
                              <div class="control">
                                  <input name="newpassword" class="input is-large" type="password" placeholder="New Password">
                              </div>
                          </div>
                          <div class="field">
                              <div class="control">
                                  <input name="conf_newpassword" class="input is-large" type="password" placeholder="Verify Password">
                              </div>
                          </div>
                          <button type="submit" class="button is-block is-primary is-large is-fullwidth">Change Password</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </section>
</body>
 
</html>
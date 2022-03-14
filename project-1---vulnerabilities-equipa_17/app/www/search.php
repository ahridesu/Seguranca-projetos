<?php
session_start();
include('db_config.php');

if(!isset($_GET["bookinfo"])){
  header("Location: main.php");
}

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
            <button type="submit">
              <a class="navbar-item">
                Change Password
              </a>
            </button>
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

<div class="box">
  <div class="content">
    <h3>Search for a book</h3>
  </div>

  <form method="GET" action="search.php">
    <input name="bookinfo" class="input is-primary" type="text" placeholder="Search" style="max-width: 96%" >
    <button class="button is-primary"><i class="fa fa-search">Search</i></button>
  </form>

  <div class="content is-medium">

  <?php
    $book = "%".trim($_GET['bookinfo'])."%";

    $query = "SELECT * FROM Book WHERE title LIKE '{$book}' OR author LIKE '{$book}' OR publisher LIKE '{$book}'";
    
    $result= mysqli_query($connection, $query);
      
    echo '<table class="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Price (€)</th>
              </tr>
            </thead>';
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        #echo $row['title']."<br>";
        $title = $row["title"];
        $author = $row["author"];
        $publisher = $row["publisher"];
        $price = $row["price"];

        echo '<tbody>
                <tr>
                  <td>'.$title.'</td>
                  <td>'.$author.'</td>
                  <td>'.$publisher.'</td>
                  <td>'.$price.'</td>
                </tr>
              </tbody>';
      }
    }
    else echo '<tbody>
                <tr>
                  <td>The book '.substr($book, 1, -1).' was not found in the database.</td>
                </tr>
              </tbody>';
    ?>      
    </table>
  </div>
</div>

</body>

</html>

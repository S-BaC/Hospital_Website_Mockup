<?php

require 'connection.php';

$docArr = refreshList($pdo);

if(isset($_POST['addDoctors'])){
  $file = $_FILES['docPhoto'];

  $fileName = $file['name'];
  $fileTmpName = $file['tmp_name'];
  
  $fileExt = explode('.', $fileName);
  $fileActExt = strtolower(end($fileExt));
  $allowedExt = array('jpg', 'jpeg', 'png');

  if(in_array($fileActExt, $allowedExt)){
    $newName = uniqid('',true) . "." . $fileActExt;
    $fileDes = "doctorImages/$newName";
    move_uploaded_file($fileTmpName, $fileDes);
    addToDatabase($pdo, $fileDes); 
    $docArr = refreshList($pdo);
  } else {
    echo "please upload jpg, jpeg or png";
  }
} else if (isset($_POST)){
  if(gettype(key($_POST)) === 'integer'){
    $docId = key($_POST);
    $query = "DELETE FROM doctorlist WHERE id = $docId";
    $sql = $pdo->prepare($query);
    $sql->execute();
    header('location:doctorlist.php');
  }
}

function addToDataBase($pdo, $fileDes){
  $query = "INSERT INTO doctorlist(doctorName, title, img_dir) VALUES(:docName, :docTitle, :docPhoto)";
  $sql = $pdo->prepare($query);
  $sql->bindValue(':docName', $_POST['docName']);
  $sql->bindValue(':docTitle', $_POST['docTitle']);
  $sql->bindValue(':docPhoto', $fileDes);
  $sql->execute();
}

function refreshList ($pdo) {
  $query = "SELECT * FROM doctorlist";
  $sql = $pdo->prepare($query);
  $sql->execute();
  return $sql->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="logo.jpg" type="image/x -icon" />
    <title>Dashboard</title>
    <script src="jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css" />
    <script
      type="module"
      src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"
    ></script>
  </head>
  <body>
    <div class="main">
      <div class="header">
        <span id="logo"><img src="logo.png" alt="" /></span>
        <span class="contact"
          >Care Center
          <p id="phone"><ion-icon name="call"></ion-icon> </p></span>
      </div>
      <div class="mainbody">
        <div class="nav">
          <div class="systemname">Sakura Hospital</div>
          <ul class="menu">
            <a href="./index.php">   <li ><ion-icon name="apps"></ion-icon> Dashboard</li></a>
          <li class="active"><ion-icon name="git-network"></ion-icon> Doctor List</li></a>
          </ul>
          <hr>
          <ul class="menu">
          </ul>
        </div>
        <div class="body">
          <p class="dashboard">Doctors</p>
          <div class="list">
              <?php
                foreach($docArr as $doc){
                  $id = $doc['id'];
                  $name = $doc['doctorName'];
                  $title = $doc['title'];
                  $img = $doc['img_dir'];
                  echo "<div class='docCard'>";
                  echo "<img src=$img alt=$name/>";
                  echo "<h3> $name </h3>";
                  echo "<p> $title </p>";
                  echo "<form action='doctorlist.php' method='POST' class='deleteForm'>";
                  echo "<input type='submit' value='Delete' name=$id>";
                  echo "</form>";
                  echo "</div>";
                }
              ?>
          </div>

          <label for="addDoctor" class="btn btnmessage">Add Doctor</label>
          <input type="checkbox" name="addDoctor" id="addDoctor">
          <form action="doctorlist.php" enctype="multipart/form-data" method="POST" id="docAdder">
            <input type="text" name="docName"/>
            <label for="docName">Name</label>
            <input type="text" name="docTitle"/>
            <label for="docTitle">Title</label>
            <input type="file" name="docPhoto"/>
            <label for="docPhoto">Photo</label>
            <input type="submit" value="Add" name="addDoctors">
          </form>

      </div>
    </div>
    <script>
    </script>
  </body>
</html>

<?php

    require 'connection.php';

    $roomsArr = refresh($pdo, 'rooms');
    $msgArr = refresh($pdo, 'messages');
    $drugArr = refresh($pdo, 'drugstore');
    $appArr = refresh($pdo, 'appointments');

    $docNum = calcNum($pdo, 'doctorlist', 'count');
    $bedNum = calcNum($pdo, 'rooms', 'sum');

    function refresh ($pdo, $table) {
      $query = "SELECT * FROM $table LIMIT 4";
      $sql = $pdo->prepare($query);
      $sql->execute();
      return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function calcNum ($pdo, $table, $func) {
      $query = $func === 'count' ? "SELECT COUNT(id) FROM $table" : "SELECT SUM(beds) FROM $table";
      $sql = $pdo->prepare($query);
      $sql->execute();
      return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    if(isset($_POST['addRooms'])){
        $insertRoom = "INSERT INTO rooms(roomStatus,beds,price) VALUE(:roomStatus, :beds, :price)";
        $sql = $pdo->prepare($insertRoom);
        $sql->bindValue(':roomStatus', $_POST['roomStatus']);
        $sql->bindValue(':beds', $_POST['roomBeds']);
        $sql->bindValue(':price', $_POST['roomPrice']);
        $sql->execute();
        $roomsArr = refresh($pdo, 'rooms');
        $bedNum = calcNum($pdo, 'rooms', 'sum');

    } else if(isset($_POST['addMessages'])){
      $insertMessage = "INSERT INTO messages(author,title,messageDes) VALUE(:author, :title, :messageDes)";
      $sql = $pdo->prepare($insertMessage);
      $sql->bindValue(':author', $_POST['msgAuthor']);
      $sql->bindValue(':title', $_POST['msgTitle']);
      $sql->bindValue(':messageDes', $_POST['msgDes']);
      $sql->execute();
      $msgArr = refresh($pdo, 'messages');
    } else if(isset($_POST['addDrugs'])){
      $insertMessage = "INSERT INTO drugstore(item,amount,itemNum, price) VALUE(:item, :amount, :itemNum, :price)";
      $sql = $pdo->prepare($insertMessage);
      $sql->bindValue(':item', $_POST['item']);
      $sql->bindValue(':amount', $_POST['amount']);
      $sql->bindValue(':itemNum', $_POST['num']);
      $sql->bindValue(':price', $_POST['itemPrice']);
      $sql->execute();
      $drugArr = refresh($pdo, 'drugstore');
    } else if(isset($_POST['addApps'])){
      $insertMessage = "INSERT INTO appointments(title,roomNum,details) VALUE(:title, :room, :details)";
      $sql = $pdo->prepare($insertMessage);
      $sql->bindValue(':title', $_POST['appTitle']);
      $sql->bindValue(':room', $_POST['appRoom']);
      $sql->bindValue(':details', $_POST['appDetails']);
      $sql->execute();
      $appArr = refresh($pdo, 'appointments');
    } else if (isset($_POST)){
      if (empty($_POST)) {
        return;
      }
      else if (gettype($_POST) === 'string'){
        $str = key($_POST);
        $cmd = ltrim($str, $str[0]);
        $code = ltrim($cmd, $cmd[0]);
        switch($cmd[0]){
          case ('R'): removeItem($pdo, $code, 'rooms'); break;
          case ('M'): removeItem($pdo, $code, 'messages'); break;
          case ('D'): removeItem($pdo, $code, 'drugstore'); break;
          case ('A'): removeItem($pdo, $code, 'appointments'); break;
        }
      } else if (gettype($_POST) === 'array'){
        $str = array_search('Update', $_POST);
        echo $str;
        $cmd = ltrim($str, $str[0]);
        $code = ltrim($cmd, $cmd[0]);
        echo "<br> $cmd[0] <br>";
        echo $code;
        switch($cmd[0]){
          case ('R'): updateItem($pdo, $code, 'rooms'); break;
          case ('M'): updateItem($pdo, $code, 'messages'); break;
          case ('D'): updateItem($pdo, $code, 'drugstore'); break;
          case ('A'): updateItem($pdo, $code, 'appointments'); break;
        }
      }
      }

    function removeItem($pdo, $code, $table){
      $query = "DELETE FROM $table WHERE id = $code";
      $sql = $pdo->prepare($query);
      $sql->execute();
    }

    function updateItem ($pdo, $code, $table) {
      $query = "UPDATE $table SET roomStatus=:roomStatus, beds=:beds, price=:price WHERE id = $code";
      $sql=$pdo->prepare($query);
      echo $_POST['roomStatus'];
      echo $_POST['beds'];
      echo $_POST['price'];
      $sql->bindValue(":roomStatus", $_POST['roomStatus']);
      $sql->bindValue(":beds", $_POST['beds']);
      $sql->bindValue(":price", $_POST['price']);
      $sql->execute();
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
    <script src="jquery3.6.0.js"></script>
    <script src="script.js"></script>
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
        <div class="nav" style="display:none">
          <div class="systemname">Sakura Hospital</div>
          <ul class="menu">
            <li class="active"><ion-icon name="apps"></ion-icon> Dashboard</li>
          <a href="./doctorlist.php">    
            <li><ion-icon name="git-network"></ion-icon> Doctor List</li></a>
          </ul>
          <hr>
          <ul class="menu">
           
          </ul>
        </div>
        <div class="body">
          <p class="dashboard">Hospital Status</p>
          <div class="h_status">
            <div class="doctor">
              <ion-icon name="git-network"></ion-icon>
              <p class="name">Doctor</p>
              <p class="count" id="dcount"><?php echo (reset($docNum[0])) ?></p>
            </div>
            <div class="nurse">
              <ion-icon name="people-outline"></ion-icon>
              <p class="name">Nurse</p>
              <p class="count" id="ncount">10</p>
            </div>
            <div class="room">
              <ion-icon name="bed-outline"></ion-icon>
              <p class="name">Bed</p>
              <p class="count" id="bcount"><?php echo (reset($bedNum[0])) ?></p>
            </div>
          </div>
          <div class="detailstatus">
            <div class="status">
              <div class="title colorprimary bgsecondary">
                <p>Room</p>
                <ion-icon name="bed-outline"></ion-icon><span id="roomTitle"> </span>
              </div>
              <table class="table" id="room">
                <?php 
                  foreach($roomsArr as $room){
                      $idRoomD = "dR".$room['id'];
                      $idRoomU = "uR".$room['id'];
                      $statusRoom = $room['roomStatus'];
                      $bedRoom = $room['beds'];
                      $priceRoom = $room['Price'];
                      echo "<tr>";
                      echo "<td> Room " . $room['id'] . "</td>";
                      echo "<td>" . $statusRoom . "</td>";
                      echo "<td>" . $bedRoom . "</td>";
                      echo "<td>" . $priceRoom . "</td>";
                      echo "<td><form action='index.php' method='POST' class='deleteForm'>";
                      echo "<input type='submit' value='Delete' name=$idRoomD>";
                      echo "</form>";

                      echo "<form action='index.php' method='POST' class='updaterForm'>";
                      echo "<input type='submit' value='Update' name=$idRoomU>";
                      echo "<input type='text' name='roomStatus' value=$statusRoom>";
                      echo "<input type='text' name='beds' value=$bedRoom>";
                      echo "<input type='text' name='price' value=$priceRoom>";
                      echo "</td></form>";
                      echo "</div>";
                      echo "</tr>";
                  } 
                ?>
              </table>
              <form action="./viewAll.php"method="POST"> 
                <input class="btn btnroom" type="submit" value="See All" name="rooms"/>
              </form>
              <label for="addRoom" class="btn btnroom">Add Room</label>
              <input type="checkbox" name="addRoom" id="addRoom">
              <form class='adderForm' action="index.php" method="POST" id="roomAdder">
                <label for="status">Status</label>
                <input type="text" name="roomStatus"/>
                <label for="beds">Beds</label>
                <input type="text" name="roomBeds"/>
                <label for="price">Price</label>
                <input type="text" name="roomPrice"/>
                <input type="submit" value="Add" name="addRooms">
              </form>
            </div>
            <div class="status">
              <div class="title bgthird">
                <p>Messages</p>
                <ion-icon name="mail-unread-outline"></ion-icon><span id="messageTitle"> </span>
              </div>
              <table class="table" id="message">
              <?php 
                  foreach($msgArr as $msg){
                      echo "<tr>";
                      echo   "<td> Room " . $msg['id'] . "</td>";
                      echo   "<td>" . $msg['author'] . "</td>";
                      echo   "<td>" . $msg['title'] . "</td>";
                      echo   "<td>" . $msg['messageDes'] . "</td>";
                      echo "</tr>";
                  } 
                ?>
              </table>
              <form action="./viewAll.php"method="POST"> 
                <input class="btn btnmessage" type="submit" value="Read More" name="messages"/>
              </form>
              <label for="addMessage" class="btn btnmessage">Add Message</label>
              <input type="checkbox" name="addMessage" id="addMessage">
              <form class='adderForm' action="index.php" method="POST" id="msgAdder">
                <label for="status">Author</label>
                <input type="text" name="msgAuthor"/>
                <label for="beds">Title</label>
                <input type="text" name="msgTitle"/>
                <label for="price">Description</label>
                <input type="text" name="msgDes"/>
                <input type="submit" value="Add" name="addMessages">
              </form>
            </div>
          </div>
          <div class="detailstatus">
            <div class="status">
              <div class="title colorprimary bgfouth">
              <ion-icon name="medkit"></ion-icon><span id="drugTitle">  Drug Store</span>
              </div>
              <table class="table">
                <?php 
                  foreach($drugArr as $drug){
                      echo "<tr>";
                      echo   "<td>" . $drug['item'] . "</td>";
                      echo   "<td>" . $drug['amount'] . "</td>";
                      echo   "<td>" . $drug['itemNum'] . "</td>";
                      echo   "<td class='price'>" . $drug['price'] . "</td>";
                      echo "</tr>";
                  } 
                ?>
              </table>
              <form action="./viewAll.php"method="POST"> 
                <input class="btn btndrug bgfouth" type="submit" value="See All" name="drugstore"/>
              </form>
              <label for="addDrug" class="btn btndrug">Add Drugs</label>
              <input type="checkbox" name="addDrug" id="addDrug">
              <form class='adderForm' action="index.php" method="POST" id="drugAdder">
                <label for="status">Item</label>
                <input type="text" name="item"/>
                <label for="beds">Amount</label>
                <input type="text" name="amount"/>
                <label for="price">Number</label>
                <input type="text" name="num"/>
                <label for="price">Price</label>
                <input type="text" name="itemPrice"/>
                <input type="submit" value="Add" name="addDrugs">
              </form>
            </div>
            <div class="status">
              <div class="title colorprimary btnappointment">
              <ion-icon name="calendar"></ion-icon><span id="appointmentTitle"> Appointment </span>
              </div>
              <table class="table">
              <?php 
                  foreach($appArr as $app){
                      echo "<tr>";
                      echo   "<td>" . $app['title'] . "</td>";
                      echo   "<td>" . $app['roomNum'] . "</td>";
                      echo   "<td class='price'>" . $app['details'] . "</td>";
                      echo "</tr>";
                  } 
                ?>
                <tr>
                  <td>Meet Dr.Yamada Uke  (Skin).</td>
                  <td>Room 102</td>
                  <td class="price">2020/12/03 09:00 AM</td>
                </tr>
                <tr>
                <td>Meet Dr.Yamazaki Shizue  (lung).</td>
                  <td>Room 202</td>
                  <td class="price">2021/01/12 07:00 PM</td>
                </tr>
                <tr>
                  <td>Meet Dr.Kanagawa Aki  (Heart).</td>
                  <td>Room 401</td>
                  <td class="price">2021/02/21 11:00 AM</td>
                </tr>
              </table>
              <form action="./viewAll.php"method="POST"> 
                <input class="btn btndrug btnappointment" type="submit" value="See All" name="appointments"/>
              </form>
              <label for="addApp" class="btn btnappointment">Add Appointment</label>
              <input type="checkbox" name="addApp" id="addApp">
              <form class='adderForm' action="index.php" method="POST" id="appAdder">
                <input type="text" name="appTitle"/>
                <label for="status">Title</label>
                <input type="text" name="appRoom"/>
                <label for="beds">Room No.</label>
                <input type="text" name="appDetails"/>
                <label for="price">Details</label>
                <input type="submit" value="Add" name="addApps">
              </form>
            </div>
        </div>
      </div>
    </div>
  </body>
</html>

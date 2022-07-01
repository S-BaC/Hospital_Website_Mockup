<?php
    require 'connection.php';

    function showAll ($pdo, $table) {
        $query = "SELECT * FROM $table";
        $sql = $pdo->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    if(isset($_POST['rooms'])){
        $table = 'rooms';    
        $arr = showAll($pdo, $table);
    } else if(isset($_POST['messages'])){
        $table = 'messages';    
        $arr = showAll($pdo, $table);
    } else if(isset($_POST['drugstore'])){
        $table = 'drugstore';    
        $arr = showAll($pdo, $table);
    } else if(isset($_POST['appointments'])){
        $table = 'appointments';    
        $arr = showAll($pdo, $table);
    }
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>
</head>
<body>
    <table>
    <?php
        if($table === 'rooms'){
            echo "<h2> Room List </h2>";
            echo "<tr>
            <th>Room ID</th>
            <th>Status</th>
            <th>Beds</th>
            <th>Price</th>
            </tr>";
            foreach($arr as $room){
                echo "<tr>";
                echo "<td> Room " . $room['id'] . "</td>";
                echo "<td>" . $room['roomStatus'] . "</td>";
                echo "<td>" . $room['beds'] . "</td>";
                echo "<td>" . $room['Price'] . "</td>";
                echo "</tr>";
            }
        } else if($table === 'messages'){
            echo "<h2> Message List </h2>";
            echo "<tr>
            <th>No.</th>
            <th>Author</th>
            <th>Title</th>
            <th>Description</th>
            </tr>";
            foreach($arr as $room){
                echo "<tr>";
                echo "<td> No." . $room['id'] . "</td>";
                echo "<td>" . $room['author'] . "</td>";
                echo "<td>" . $room['title'] . "</td>";
                echo "<td>" . $room['messageDes'] . "</td>";
                echo "</tr>";
            }
        } else if($table === 'drugstore'){
            echo "<h2> Drug Store </h2>";
            echo "<tr>
            <th>Item</th>
            <th>Amount</th>
            <th>No.</th>
            <th>Price</th>
            </tr>";
            foreach($arr as $drug){
                echo "<tr>";
                echo "<td>" . $drug['item'] . "</td>";
                echo "<td>" . $drug['amount'] . "</td>";
                echo "<td>" . $drug['itemNum'] . "</td>";
                echo "<td>" . $drug['price'] . "</td>";
                echo "</tr>";
            }
        } else if($table === 'appointments'){
            echo "<h2> Room List </h2>";
            echo "<tr>
            <th>Title</th>
            <th>Room</th>
            <th>Detials</th>
            </tr>";
            foreach($arr as $app){
                echo "<tr>";
                echo "<td>" . $app['title'] . "</td>";
                echo "<td>" . $app['roomNum'] . "</td>";
                echo "<td>" . $app['details'] . "</td>";
                echo "</tr>";
            }
        }
    ?>
    </table>
</body>
</html>
<!DOCTYPE HTML>
<html><head><title>Delete Room</title> </head>
 <body>

<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}

//retrieve the Roomid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Room ID</h2>"; //simple error feedback
        exit;
    } 
}

//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     
    $error = 0; //clear our error flag
    $msg = 'Error: ';  
//RoomID (sent via a form it is a string not a number so we try a type conversion!)    
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Room ID '; //append error message
       $id = 0;  
    }        
    
//save the Room data if the error flag is still clear and Room id is > 0
    if ($error == 0 and $id > 0) {
        $query = "DELETE FROM Room WHERE RoomID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'i', $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Room details deleted.</h2>";     
        
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      

}

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT booking.Booking_ID, booking.Checkin_date, booking.Checout_date, room.roomname, booking.Contact_number, booking.Booking_extra
FROM booking, customer, room
WHERE booking.customerID = customer.customerID AND booking.roomID = room.roomID
ORDER BY Booking_ID';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Room Details View</h1>
<h2><a href='listrooms.php'>[Return to the Room listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>
<?php

//makes sure we have the Room
if ($rowcount > 0) {  
   echo "<fieldset><legend>Room detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
   echo "<dt>Checkin_date:</dt><dd>".$row['Checkin_date']."</dd>".PHP_EOL;
   echo "<dt>Contact_number:</dt><dd>".$row['Contact_number']."</dd>".PHP_EOL;
   echo "<dt>Booking_extra:</dt><dd>".$row['Booking_extra']."</dd>".PHP_EOL;
   echo '</dl></fieldset>'.PHP_EOL;  
   
} else echo "<h2>No Booking Found !</h2>";
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
</body>
</html>

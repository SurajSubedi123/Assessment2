<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Listings</title>
</head>
 
<body>
 
    <?php
    include "config.php"; // Make sure to include your DB connection file

    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; // Stop processing the page further if connection fails
    }
 
    // SQL query to fetch rooms data
    $query = 'SELECT booking.Booking_ID, booking.Checkin_date, booking.Checout_date, customer.firstname, customer.lastname, room.roomname
    FROM booking, customer, room
    WHERE booking.customerID = customer.customerID AND booking.roomID = room.roomID
    ORDER BY Booking_ID';
    
    $result = mysqli_query($DBC, $query); 

    // Check if the query was successful
    if (!$result) {
        die('Query Error: ' . mysqli_error($DBC)); // Output error if query fails
    }

    $rowcount = mysqli_num_rows($result);
    ?>
 
    <h1>Room Listings</h1>
    <h2><a href="makebooking.php">[Make a Booking]</a><a href="index.php">[Return to Main Page]</a></h2>
 
    <table border="1">
        <thead>
        <tr>
                <th>Booking (room, dates)</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // If there are rooms, display them in the table

            if ($rowcount > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['Booking_ID'];
                    echo '<tr><td>' . $row['roomname'] . ','." " . $row['Checkin_date'] . ',' ." ". $row['Checout_date'] . '</td>' .
                        '<td>' . $row['firstname'] . ','." " . $row['lastname'] . '</td>' .
                        '<td><a href="viewbooking.php?id=' . $id . '">[view]</a>' ."_".
                        '<a href="editbooking.php?id=' . $id . '">[edit]</a>' ."_".
                        '<a href="editroom.php?id=' . $id . '">[Manage Review]</a>' ."_".
                        '<a href="deletebooking.php?id=' . $id . '">[delete]</a></td>';
                    echo '</tr>' . PHP_EOL;
                }
            } else
                echo "<h2>No tickets found!</h2>"; //suitable feedback
            
            mysqli_free_result($result);
            mysqli_close($DBC);
            ?>
        </tbody>
    </table>
</body>
</html>

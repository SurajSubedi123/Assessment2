<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
            });
            $(function() {
                $("#depa").datepicker();
                $("#arr").datepicker();
            });
        });
    </script>
</head>

 
<body>
    <?php
    include "config.php"; // Ensure config.php includes your database credentials and settings
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
   
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL." . mysqli_connect_error();
        exit;
    }
 
    function cleanInput($data)
    {
        global $DBC;
        return mysqli_real_escape_string($DBC, htmlspecialchars(stripslashes(trim($data))));
    }
 
    // Check if ID exists and is numeric
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid booking ID</h2>";
            exit;
        }
 
        // Fetch booking details for the given ID
        $query = "SELECT booking.Booking_ID, room.roomname, booking.Checkin_date, booking.Checout_date, booking.Contact_number, booking.Booking_extra, booking.Room_Review
        FROM booking
        LEFT JOIN room ON booking.RoomID = room.roomID
        WHERE booking.Booking_ID = ?";


// Prepare the statement
$stmt = mysqli_prepare($DBC, $query);
if (!$stmt) {
  die("Error preparing statement: " . mysqli_error($DBC));
}

// Bind the parameters and execute the statement
mysqli_stmt_bind_param($stmt, 'i', $id);
if (!mysqli_stmt_execute($stmt)) {
  die("Error executing query: " . mysqli_error($DBC));
}

// Fetch the results
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
  die("Error fetching results: " . mysqli_error($DBC));
}

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
       
        // Check if booking exists
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "<h2>Booking not found</h2>";
            exit;
        }
 
        mysqli_stmt_close($stmt);
    }
 
    // On form submission, update booking details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $depa = cleanInput($_POST['depa']);
        $arr = cleanInput($_POST['arr']);
        $contactNumber = cleanInput($_POST['contactNumber']);
        $bookingExtra = cleanInput($_POST['bookingExtra']);
        $roomReview = cleanInput($_POST['roomReview']);
        $id = cleanInput($_POST['id']);
 
        $query = "UPDATE booking SET Checkin_date=?, Checkout_date=?, Contact_number=?, Booking_extra=?, Room_review=? WHERE BookingID=?";
        $stmt = mysqli_prepare($DBC, $query);
        mysqli_stmt_bind_param($stmt, 'sssssi', $depa, $arr, $contactNumber, $bookingExtra, $roomReview, $id);
       
        if (mysqli_stmt_execute($stmt)) {
            echo "<h5>Booking updated successfully.</h5>";
        } else {
            echo "Error updating booking: " . mysqli_error($DBC);
        }
 
        mysqli_stmt_close($stmt);
    }
 
    mysqli_close($DBC);
    ?>
 
    <h1>Update Booking</h1>
    <h2>
        <a href="listbookings.php">[Return to the booking list]</a>
        <a href="index.php">[Return to main page]</a>
    </h2>
    <div>
        <form method="POST">
            <p>
                <label for="roomname">Room:</label>
                <input type="text" id="roomname" name="roomname" value="<?php echo htmlspecialchars($row['roomname']); ?>" readonly>
            </p>
            <p>
                <label for="depa">Check-in Date:</label>
                <input type="text" id="depa" name="depa" required value="<?php echo htmlspecialchars($row['Checkin_date']); ?>">
            </p>
            <p>
                <label for="arr">Check-out Date:</label>
                <input type="text" id="arr" name="arr" required value="<?php echo htmlspecialchars($row['Checout_date']); ?>">
            </p>
            <p>
                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($row['Contact_number']); ?>">
            </p>
            <p>
                <label for="bookingExtra">Booking Extra:</label>
                <input type="text" id="bookingExtra" name="bookingExtra" value="<?php echo htmlspecialchars($row['Booking_extra']); ?>">
            </p>
            <p>
                <label for="roomReview">Room Review:</label>
                <input type="text" id="roomReview" name="roomReview" value="<?php echo htmlspecialchars($row['Room_Review']); ?>">
            </p>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submit" value="Update">
        </form>
    </div>
</body>
 
</html>

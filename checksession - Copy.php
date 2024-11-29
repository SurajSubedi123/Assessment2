<?php

session_start();

// Function to check if user is logged in
function checkUser() {
    $_SESSION['URI'] = '';
    if ($_SESSION['loggedin'] == 1) {
        return true;
    } else {
        $_SESSION['URI'] = '/' . $_SERVER['REQUEST_URI'];
        header('Location: login.php', true, 303);
        exit; // Make sure to exit after header redirection
    }
}


// Function to display login status
function loginStatus() {
    if ($_SESSION['loggedin'] == 1) {
        $firstname = $_SESSION['firstname'];
        echo "<h6>Logged in as $firstname</h6>";
    } else {
        echo "<h6>Logged out</h6>";
    }
}

// Function to simulate login process (replace with actual login logic)
function login($id, $firstname) {
    if ($_SESSION['loggedin'] == 0 && !empty($_SESSION['URI'])) {
        $uri = $_SESSION['URI'];
    } else {
        $_SESSION['URI'] = 'listbookings.php';
        $uri = $_SESSION['URI'];
    }

    $_SESSION['loggedin'] = 1;
    $_SESSION['userid'] = $id;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['URI'] = '';

    header('Location: listbookings.php', true, 303);
    exit; // Make sure to exit after header redirection
}

// Function to simulate logout process
function logout() {
    $_SESSION['loggedin'] = 0;
    $_SESSION['userid'] = -1;
    $_SESSION['firstname'] = '';
    $_SESSION['URI'] = '';
    header('Location: login.php', true, 303);
    exit; // Make sure to exit after header redirection
}

?>

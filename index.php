<?php
session_start();
$isRegistered = 0;
require("dbaccess.php");

if(isset($_POST["username"]) && isset($_POST["password"])) {
    $enteredUsername = $_POST["username"];
    $enteredPassword = $_POST["password"];

    // Connect to the database
    $db_obj = new mysqli($host, $user, $password, $database);
    if ($db_obj->connect_error) {
        echo "Connection Error: " . $db_obj->connect_error;
        exit();
    }

    // Prepare a query to get user information
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $db_obj->prepare($query);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the entered username exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the entered password matches the stored hashed password
        if (password_verify($enteredPassword, $user['password']) && $user['status'] == "aktiv") {
            // Login successful, store username in the session
            $_SESSION["usernameSession"] = $enteredUsername;
            $_SESSION["userid"] = $user['id'];
            $_SESSION["uservorname"] = $user['vorname'];
            $_SESSION["usertyp"] = $user['typ'];
            //$_SESSION["usernachname"] = $user['nachname'];
            //$_SESSION["useremail"] = $user['useremail'];

            // Redirect to the home page after successful login
            header("Location: index.php?page=home");
        }
    }

    // Close the database connection
    $stmt->close();
    $db_obj->close();
}


// Lade Seiten
$page = "404.php";
$p = '';
if(isset($_GET['page']))
{
    $p = $_GET['page'];
}

if($p == '' || $p == 'home')
{
    $page = 'home.php';
}

if($p == 'help')
{
    $page = 'help.php';
}

if($p == 'login')
{
    $page = 'login.php';
}

if($p == 'registrierung')
{
    $page = 'registrierung.php';
} 

if($p == 'impressum')
{
    $page = 'impressum.php';
}

if($p == 'buchen')
{
    $page = 'buchen.php';
}

if($p == 'news-beitrag')
{
    $page = 'news-beitrag.php';
}

if($p == 'logout')
{
    $page = 'logout.php';
}

if($p == 'reservierungen')
{
    $page= 'reservierungen.php';
}
if($p == 'reservierungs-verwaltung')
{
    $page= 'reservierungs-verwaltung.php';
}
if($p == 'profile')
{
    $page = 'profile.php';
}

if($p == 'news')
{
    $page = 'news.php';
}

if($p == 'admin')
{
    $page = 'admin.php';
}

if($p == 'reservierungen_einzeln')
{
    $page = 'reservierungen_einzeln.php';
}

if($p == 'reservierung_details')
{
    $page = 'reservierung_details.php';
}


if($p == 'logout')
{


    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or any other page after logout
    header("Location: index.php?page=home");
    echo "Sie sind jetzt abgemeldet";
    exit();
    

}


/*
if (isset($user) && isset($enteredPassword))
{
    if ($p=='login' && $user && password_verify($enteredPassword, $user['password'])) {
        // Perform any additional actions upon successful login
        header("Location: index.php?page=home");
        //exit();
    } else {
        // Display error message for invalid username or password
        //echo "Username oder Passwort existiert nicht";
    }
}
*/

// Redirect to home after successful registration
if(isset($_POST["anrede"]) && isset($_POST["vorname"]) && isset($_POST["nachname"]) && isset($_POST["email"]) && isset($_POST["usernameR"]))
{
    if ($isRegistered == 1 && $p=='registrierung' && $_POST["anrede"] && $_POST["vorname"] && $_POST["nachname"] && $_POST["email"] && $_POST["usernameR"])
    {
        //echo "Registrierung erfolgreich! Sie werden gleich zur Homepage weitergeleitet!";
        sleep(1);
        header("Location: index.php?page=login");
    }
}


include('header.php');
include('nav.php');

include($page);

// Lade Footer

include('footer.php');


?>
<?php
session_start();
require("dbaccess.php");

$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
    echo "Connection Error: " . $db_obj->connect_error;
    exit();
}

// logged in user information
$newVorname = $_POST['newVorname'];
$newNachname = $_POST['newNachname'];
$newEmail = $_POST['newEmail'];
$newUsername = $_POST['newUsername'];


if (isset($_POST['newVorname'])) {
    $sql = "UPDATE users SET vorname = '$newVorname' WHERE username = '{$_SESSION['usernameSession']}'";
    $success_message = "Vorname updated successfully!";
    $_SESSION['vorname_update_success'] = $success_message;
} elseif (isset($_POST['newNachname'])) {
    $sql = "UPDATE users SET nachname = '$newNachname' WHERE username = '{$_SESSION['usernameSession']}'";
    $success_message = "Nachname updated successfully!";
    $_SESSION['nachname_update_success'] = $success_message;
} elseif (isset($_POST['newEmail'])) {
    $sql = "UPDATE users SET useremail = '$newEmail' WHERE username = '{$_SESSION['usernameSession']}'";
    $success_message = "Email address updated successfully!";
    $_SESSION['email_update_success'] = $success_message;
} elseif (isset($_POST['newUsername'])) {
    $sql = "UPDATE users SET username = '$newUsername' WHERE username = '{$_SESSION['usernameSession']}'";
    $success_message = "Username updated successfully!";
    $_SESSION['username_update_success'] = $success_message;
}

$result = $db_obj->query($sql);

if ($result) {
    header("Location: index.php?page=profile");
} else {
    $_SESSION['update_error'] = "Error: " . $db_obj->error;
    header("Location: index.php?page=profile");
}

$db_obj->close();
?>

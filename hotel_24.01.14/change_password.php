<?php
session_start();
require("dbaccess.php");

$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
    echo "Connection Error: " . $db_obj->connect_error;
    exit();
}

$newPassword = $_POST['newPassword'];
$newPasswordAgain = $_POST['newPasswordAgain'];
$oldPassword = $_POST['oldPassword'];

$sql2 = "SELECT * FROM users WHERE username = '{$_SESSION['usernameSession']}'";
$result = $db_obj->query($sql2);
$row = $result->fetch_array();

if (isset($_POST['newPassword']) && isset($_POST['newPasswordAgain'])) {
    if($_POST["newPassword"] != $_POST["newPasswordAgain"]) {
        $error_message = "Passwörter stimmen nicht überein.";
        $_SESSION['password_update_error'] = $error_message;
    } else {
        if(password_verify($oldPassword, $row['password'])) {
            $newPassHashed = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE username = ?";
            $stmt = $db_obj->prepare($sql);
            $stmt->bind_param("ss", $newPassHashed, $_SESSION['usernameSession']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success_message = "Password updated successfully!";
                $_SESSION['password_update_success'] = $success_message;
            } else {
                $_SESSION['update_error'] = "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Das alte Passwort stimmt nicht.";
            $_SESSION['password_update_error'] = $error_message;
        }

        
    }
}

$db_obj->close();
header("Location: index.php?page=profile");
?>

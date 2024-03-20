<?php
require_once ('dbaccess.php');
if(isset($_POST["username"]) && !empty($_POST["username"])
    && isset($_POST["password"]) && !empty($_POST["password"])
    && isset($_POST["useremail"]) && !empty($_POST["useremail"])) 
{
        $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
        //create $db_obj, create sql statement, prepare it and bind the variables to it
        $uname = $_POST["username"];
        $pass = $_POST["password"];
        $mail = $_POST["useremail"];
        if ($stmt->execute()) { echo "New user created"; } else { echo "Error"; }
        $stmt->close(); $db_obj->close();
}  
?>
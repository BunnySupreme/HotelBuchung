<?php
require_once ('dbaccess.php'); //to retrieve connection details
$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
echo "Connection Error: " . $db_obj->connect_error;
exit();
}
?>

<?php
if (isset($_POST["usernameR"], $_POST["passwordR"], $_POST["email"], $_POST["vorname"], $_POST["nachname"], $_POST["anrede"])) {
    $usernameR = $_POST["usernameR"];
    $passwordplaintext = $_POST["passwordR"];
    $email = $_POST["email"];
    $vorname = $_POST["vorname"];
    $nachname = $_POST["nachname"];
    $anrede = $_POST["anrede"];

    // encrypt password
    $hashvalue = password_hash($passwordplaintext, PASSWORD_DEFAULT);
    $passwordR = $hashvalue;
 
}
?>



<?php

$msg = '';
if(isset($_POST["passwordR"]) && isset($_POST["passwordagain"]))
{
    if($_POST["passwordR"] != $_POST["passwordagain"])
    {
        $msg = "Passwörter stimmen nicht überein.";
    }
    else
    {
        if(isset($_POST["anrede"]) && isset($_POST["vorname"]) && isset($_POST["nachname"]) && isset($_POST["email"]) && isset($_POST["usernameR"]))
        {

            $sql_check = "SELECT * FROM `users` WHERE `username` = ?";
            $stmt_check = $db_obj->prepare($sql_check);
            $stmt_check-> bind_param("s", $usernameR);
            $stmt_check->execute();
            $stmt_check_result = $stmt_check->get_result();

            if ($row_check = $stmt_check_result->fetch_assoc()) {
                $msg = 'User existiert schon!';
            }

            else
            {
                $sql = "INSERT INTO `users` (`username`, `password`, `useremail`, `vorname`, `nachname`, `anrede`)
                VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db_obj->prepare($sql);
                $stmt-> bind_param("ssssss", $usernameR, $passwordR, $email, $vorname, $nachname, $anrede);
                $stmt->execute();
                $msg = "Registrierung erfolgreich! Sie werden gleich zur Login-Seite weitergeleitet!";
                $stmt->close(); $db_obj->close();
                echo '<meta http-equiv="Refresh" content="3;./index.php?page=login">';
                
                
                
            }
           
            
        }
    }
}






?>

<br>

<h4 class="text-center"><?php echo $msg; ?></h4>

<div class="container" style="max-width: 400px;">
    <div class="card card-body p-4 bg-dark text-white" style="border-radius: 1rem;">
        <div class = form-signin>
            <p class="text-white lead p-1">Anrede:</p>
            <form role = form action="" method="post" autocomplete="on">
                <input type="radio" id="herr" name="anrede" value="Herr" <?php if(isset($_POST["anrede"]) && $_POST["anrede"] == "Herr") echo 'checked="checked"'; ?> required>
                <label for="herr">Herr</label><br>

                <input type="radio" id="frau" name="anrede" value="Frau" <?php if(isset($_POST["anrede"]) && $_POST["anrede"] == "Frau") echo 'checked="checked"'; ?> required>
                <label for="frau">Frau</label><br>

                <input type="radio" id="divers" name="anrede" value="divers" <?php if(isset($_POST["anrede"]) && $_POST["anrede"] == "divers") echo 'checked="checked"'; ?> required>
                <label for="divers">divers</label><br>
            <br>
                <label for="vorname" class="text-white lead p-1">Vorname:</label>
                <input type="text" id="vorname" name="vorname" value="<?php if(isset($_POST["vorname"])) echo $_POST["vorname"];?>" class="form-control" required>

                <label for="nachname" class="text-white lead p-1">Nachname:</label>
                <input type="text" id="nachname" name="nachname" value="<?php if(isset($_POST["nachname"])) echo $_POST["nachname"];?>" class="form-control" required>

                <label for="email" class="text-white lead p-1">Email Adresse:</label>
                <input type="email" id="email" name="email" value="<?php if(isset($_POST["email"])) echo $_POST["email"];?>" class="form-control" required>

                <label for="usernameR" class="text-white lead p-1">Username:</label>
                <!-- Below, added "R" to the id and name, to differentiate from username and password for login-->
                <input type="text" id="usernameR" name="usernameR" value="<?php if(isset($_POST["usernameR"])) echo $_POST["usernameR"];?>" class="form-control" required>

                <label for="passwort" class="text-white lead p-1">Passwort:</label>
                <input type="password" id="passwordR" name="passwordR" class="form-control" required>

                <label for="passwortR" class="text-white lead p-1">Passwort nochmal:</label>
                <input type="password" id="passwordagain" name="passwordagain" class="form-control" required>
                
                <br>
                <button class="btn btn-lg btn-success btn-block" type="submit" name="registrieren">Registrieren</button>
            </form>
        </div>
    </div>
</div>


<?php
exit();
?>
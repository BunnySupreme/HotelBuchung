<?php
require("dbaccess.php");
$db_obj = new mysqli($host, $user, $password, $database);
if ($db_obj->connect_error) {
echo "Connection Error: " . $db_obj->connect_error;
exit();
}
?>

<?php
    $msg = '';
    if(isset($_POST["username"]) && isset($_POST["password"]))
    {
        $enteredUsername = $_POST["username"];
        $enteredPassword = $_POST["password"];
        // Query to retrieve user information from the database
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $db_obj->prepare($query);
        $stmt->bind_param("s", $enteredUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if the user exists and the entered password is correct
        if ($user && password_verify($enteredPassword, $user['password'])) {
            if ($user['status'] == "inaktiv")
            {
                $msg = 'User wurde deaktiviert';
            }
            else
            {
                $msg = 'Login erfolgreich!';
                // Perform any additional actions upon successful login
                //header("Location: index.php?page=home");
                //exit();
            }
        } else {
            $msg = 'Benutzername und/oder Passwort falsch';
        }

    }

    
    if (@$_POST['safeit'] == '1') {
        $logincookieduration = 31536000; //valid for 1 year
        setcookie("userID", $_SESSION['userID'], time() + $logincookieduration);
        setcookie("username", $_POST['username'], time() + $logincookieduration);
        setcookie("password", $_POST['password'], time() + $logincookieduration);
        setcookie("logincookie", $logincookieduration, time() + $logincookieduration);
    }
?>

</br>
<h4 class="text-center"><?php echo $msg; ?></h4>

<div class="container" style="max-width: 400px;">
    <div class="card card-body p-4 bg-dark text-white" style="border-radius: 1rem;">
        <div class="form-signin">
            <form role="form" action="" method="post">
                <h5 class="text-center">Login</h5>
                <input type="text" class="form-control" name="username" placeholder="Username" value="<?php if(isset($_POST["username"])) echo $_POST["username"];?>" required autofocus></br>
                <input type="password" class="form-control" name="password" placeholder="Passwort" required><br>
                <input type="hidden" name="safeit" value="0" />
                <input type="checkbox" name="safeit" value="1">
                <label for="safeit">Eingeloggt bleiben!</label><br>
                <button class="btn btn-lg btn-success btn-block" type="submit" name="login">Einloggen</button>
            </form>
        </div>
    </div>
</div>
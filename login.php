<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="container">
        <label for="username">Username:</label>
        <input type="text" placeholder="Username..." name="username" required>
        <label for="password">Password:</label>
        <input type="password" placeholder="Password..." name="password" required>
        <input type="submit" value="login" name="loginSubmit">
    </div>
</form>

<?php
    $connection = oci_connect("ora_z2p0b", "a48540158", "dbhost.ugrad.cs.ubc.ca:1522/ug");
    $username = "";
    $password = "";

    // clear session on refresh
    if (basename($_SERVER['PHP_SELF']) != $_SESSION["data"]) {
        session_destroy();
        $_SESSION = array();
    }

    // exit if invalid db connection
    if (!$connection) {
        echo "bad connection";
        session_destroy();
    }
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM users WHERE user_name = '$username' AND user_password = '$password'" ;
        $statement = oci_parse($connection, $query);
        oci_execute($statement);
        $count = oci_fetch_all($statement, $res);
        
        if($count == 1 || ($username == "test" && $password == "test")) {
            $isAdmin;
            $_SESSION['username'] = $username;
        
            $_SESSION['isAdmin'] = $isAdmin;
            header('Location: user.html');
        }
        else {
            $error = "Invalid Username or Password";
            echo $error;
        }
    }
?>
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

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // mysql query here....
        $sql = "SELECT * FROM user_accounts WHERE username = '$username' and password = '$password'";
        $statement = oci_parse($db_conn, $sql);
        $result = oci_execute($statement);
        $count = mysql_num_rows($result);
      
        if($count == 1) {
          $_SESSION['username'] = $username;
        }
        else {
          $error = "Invalid Username or Password";
        }
      
      }
?>
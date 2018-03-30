<?php 
    session_start();
    $userId = isset($userId) ? $userId : '';
    $username = $_GET['user'];
    $userEmail = isset($userEmail) ? $userEmail : '';
    $userBalance = isset($userBalance) ? $userBalance : '';
    $userTransactions = isset($userTransactions) ? $userTransactions : '';

    $connection = oci_connect("ora_z2p0b", "a48540158", "dbhost.ugrad.cs.ubc.ca:1522/ug");



    function getUserInfo() {
        global $userId, $username, $userEmail, $userBalance, $userTransactions, $connection;

        $query = "SELECT user_id, user_email, user_balance FROM users WHERE user_name = '$username'";
        $statement = oci_parse($connection, $query);
        
        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        else {
            $result = oci_fetch_object($statement);
            $userId = $result->USER_ID;
            $userEmail = $result->USER_EMAIL;
            $userBalance = $result->USER_BALANCE;
        }      
    }

    if(isset($_POST['balanceSubmit'])){
        $addBalance =  $_POST["BalanceAmount"];
        $creditcard = $_POST["CCNumBalance"];
        getUserInfo();
        addBalance($addBalance, $creditcard);
    }

    function isValidCreditCard($creditcard){
        global $connection, $username, $userId;
        $flag = 'false';

        $query = "SELECT creditcard_num FROM billing_info WHERE user_id = '$userId'";
        $statement = oci_parse($connection, $query);


        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
            echo "error";
        }else {
            while(($row = oci_fetch_object($statement)) != False){
                $userCreditCard = $row->CREDITCARD_NUM;
                if($userCreditCard == $creditcard){
                    $flag = 'true';
                    return $flag;
                }
            }
        }
        return $flag;
    }

    function addBalance($addBalance, $creditcard){
        global $userBalance, $connection, $username;

        if(isValidCreditCard($creditcard) == 'false'){
            echo "Credit card not on file";
            return;
        }

        $newBalance = $addBalance + $userBalance; 

        $query = "UPDATE users SET user_balance = '$newBalance' WHERE user_name = '$username'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
    }

    /*DEGUB
    function checkCredit(){
        global $userBalance, $connection, $username;

        $query = "SELECT user_creditcard FROM users WHERE user_name = '$username'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }else {
            $result = oci_fetch_object($statement);
            $userBalance = $result->user_creditcard;
            echo $userBalance;
        }
    }

    function checkBalance(){
        global $userBalance, $connection, $username;

        $query = "SELECT user_balance FROM users WHERE user_name = '$username'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }else {
            $result = oci_fetch_object($statement);
            $userBalance = $result->USER_BALANCE;
            echo $userBalance;
        }
    }*/

    function getBuyerTransactions() {
        global $userId, $connection;

        $query = "SELECT transaction_id, purchase_date, purchase_price, user_name, market_item_id
                  FROM transaction_supervises, users
                  WHERE buyer_id = '$userId' and user_id = seller_id";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function getSellerTransactions() {
        global $userId, $connection;

        $query = "SELECT transaction_id, purchase_date, purchase_price, user_name, market_item_id
                  FROM transaction_supervises, users
                  WHERE seller_id = '$userId' and user_id = buyer_id";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function getGamesInventory() {
        global $userId, $connection;

        $query = "SELECT game_title, game_purchase_date FROM game, market_item WHERE game_id = item_id and user_id = '$userId'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function getItemsInventory() {
        global $userId, $connection;

        $query = "SELECT g.game_title, i.item_name, i.item_description, i.item_quantity 
                  FROM item_belongsto i, market_item m, game g 
                  WHERE i.item_id = m.item_id and m.user_id = 4 and i.game_id = g.game_id";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function printBuyerTransactions($result) { //prints results from a select statement
        echo "<tr><th>Transaction ID</th>
                  <th>Purchase Date</th>
                  <th>Purchase Price</th>
                  <th>Buyer Name</th>
                  <th>Item ID</th>
             </tr>";
    
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->TRANSACTION_ID . "</td>
                      <td>" . $row->PURCHASE_DATE . "</td>
                      <td>" . "$" . number_format((float)$row->PURCHASE_PRICE, 2, '.', '') . "</td>
                      <td>" . $row->USER_NAME . "</td>
                      <td>" . $row->MARKET_ITEM_ID . "</td>
                  </tr>";        
        }
        echo "</table>";
    }

    function printSellerTransactions($result) { //prints results from a select statement
        echo "<table>";
        echo "<tr><th>Transaction ID</th>
                  <th>Purchase Date</th>
                  <th>Purchase Price</th>
                  <th>Seller Name</th>
                  <th>Item ID</th>
             </tr>";
    
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->TRANSACTION_ID . "</td>
                      <td>" . $row->PURCHASE_DATE . "</td>
                      <td>" . "$" . number_format((float)$row->PURCHASE_PRICE, 2, '.', '') . "</td>
                      <td>" . $row->USER_NAME . "</td>
                      <td>" . $row->MARKET_ITEM_ID . "</td>
                  </tr>";        
        }
        echo "</table>";
    }

    function printGamesInventory($result) { //prints results from a select statement
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->GAME_TITLE . "</td>
                      <td>" . $row->GAME_PURCHASE_DATE . "</td>
                  </tr>";        
        }
    }

    function printItemsInventory($result) { //prints results from a select statement
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->GAME_TITLE . "</td>
                      <td>" . $row->ITEM_NAME . "</td>
                      <td style='width:20%'>" . $row->ITEM_DESCRIPTION . "</td>
                      <td>" . $row->ITEM_QUANTITY . "</td>
                  </tr>";        
        }
    }

    if ($connection) {
        getUserInfo();
    }
?>

<h3>User Info </h3>
<table style="width:30%">
    <tr><td> ID: </td> <td><?php echo $userId; ?></td></tr>
    <tr><td> Username: </td> <td><?php echo $username; ?></td></tr>
    <tr><td> Email: </td> <td><?php echo $userEmail; ?></td></tr>
    <tr><td> Balance: </td> <td> <?php echo "$"; echo number_format((float)$userBalance, 2, '.', ''); ?></td></tr>
</table>
<br>
<br>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 50%;
    border-spacing: 5px;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>

<h3>Purchase History: </h3>

<table>
<?php 
    $result = getBuyerTransactions();
    printBuyerTransactions($result); 
?>
<br>
<br>
<h3>Sales History: </h3>
<?php
    $result = getSellerTransactions();
    printSellerTransactions($result); 
?>

<h1>Add Balance</h1>
<form method="POST" action="<?php echo "user.php?user=$username" ?>" >
    <div class="container">
        <label for="CCNumBalance">Credit Card Number</label>
        <input type="text" placeholder="Credit Card Number" name="CCNumBalance" required>
        <br>
        <label for="BalanceAmount">Amount to Add</label>
        <input type="text" placeholder="Amount to Add" name="BalanceAmount" required>
        <br>
        <input type="submit" value="Submit" name="balanceSubmit">
    </div>
</form>
<h1>Billing</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Credit Card Number</th>
        <th>Expiry Date</th>
        <th>CVV</th>
        <th>Cardholder Name</th>
        <th>Address</th>
        <th>Phone Number</th>
    </tr>
</table>
<p>Add a new credit card.</p>
<form method="POST" action="user.php">
    <div class="container">
        <label for="CCNum">Credit Card Number</label>
        <input type="text" placeholder="Credit Card Number" name="CCNum" required>
        <br>
        <label for="CCExpDate">Expiry Date</label>
        <input type="text" placeholder="Expiry Date" name="CCExpDate" required>
        <br>
        <label for="CVV">CVV</label>
        <input type="text" placeholder="CVV" name="CVV" required>
        <br>
        <label for="CCName">Cardholder Name</label>
        <input type="text" placeholder="Name" name="CCName" required>
        <br>
        <label for="Address">Address</label>
        <input type="text" placeholder="Address" name="Address" required>
        <br>
        <label for="CCPhoneNum">Phone Number</label>
        <input type="text" placeholder="Phone Number" name="CCPhoneNum" required>
        <br>
        <input type="submit" value="Insert" name="billingInsert">
    </div>
</form>
<p>Update an existing credit card.</p>
<form method="POST" action="user.php">
    <div class="container">
        <label for="CCID">Credit Card ID</label>
        <input type="text" placeholder="Credit Card ID" name="CCID" required>
        <br>
        <label for="CCNumUpdate">Credit Card Number</label>
        <input type="text" placeholder="Credit Card Number" name="CCNumUpdate" required>
        <br>
        <label for="CCExpDateUpdate">Expiry Date</label>
        <input type="text" placeholder="Expiry Date" name="CCExpDateUpdate" required>
        <br>
        <label for="CVVUpdate">CVV</label>
        <input type="text" placeholder="CVV" name="CVVUpdate" required>
        <br>
        <label for="CCNameUpdate">Cardholder Name</label>
        <input type="text" placeholder="Name" name="CCNameUpdate" required>
        <br>
        <label for="AddressUpdate">Address</label>
        <input type="text" placeholder="Address" name="AddressUpdate" required>
        <br>
        <label for="CCPhoneNumUpdate">Phone Number</label>
        <input type="text" placeholder="Phone Number" name="CCPhoneNumUpdate" required>
        <br>
        <input type="submit" value="Update" name="billingUpdateSubmit">
    </div>
</form>
<br>
<br>

<h3>Game Inventory</h3>

<table>
    <tr>
        <th>Title</th>
        <th>Purchase Date</th>
    </tr>
    <?php
        $result = getGamesInventory();
        printGamesInventory($result);
    ?>
</table>
<br>
<br>

<h3>Item Inventory</h3>

<table style="width:100%">
    <tr>
        <th>Game Title</th>
        <th>Item Name</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Average Listed Price</th>
        <th>Highest Listed Pirce</th>
        <th>Lowest Listed Price</th>        
    </tr>
    <?php
        $result = getItemsInventory();
        printItemsInventory($result);
    ?>
</table>
<h1>Personal Listings</h1>
<table>
    <tr>
        <th>Market Item ID</th>
        <th>Item Name</th>
        <th>Listed Date</th>
        <th>Listed Price</th>
        <th>Quantity</th>
    </tr>
</table>
<p>Select an item ID and sell the item.</p>
<form method="POST" action="user.php">
    <div class="container">
        <label for="ItemIDSell">Item ID</label>
        <input type="text" placeholder="Item ID" name="ItemIDSell" required>
        <br>
        <label for="Quantity">Quantity</label>
        <input type="text" placeholder="Quantity" name="Quantity" required>
        <br>
        <label for="Price">Price</label>
        <input type="text" placeholder="Price" name="Price" required>
        <br>
        <input type="submit" value="Sell" name="sellSubmit">
    </div>
</form>
<p>Select an item ID to update an existing listing.</p>
<form method="POST" action="user.php">
    <div class="container">
        <label for="ItemIDSellUpdate">Item ID</label>
        <input type="text" placeholder="Item ID" name="ItemIDSellUpdate" required>
        <br>
        <label for="PriceUpdate">Price</label>
        <input type="text" placeholder="Price" name="PriceUpdate" required>
        <br>
        <input type="submit" value="Update" name="sellUpdateSubmit">
    </div>
</form>
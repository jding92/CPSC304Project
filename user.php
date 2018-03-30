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
    
    function getBillingInfo() {
        global $userId, $connection;

        $query = "select * from billing_info where user_id = '$userId'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
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
            echo "Error: Credit card not on file. Please use an existing card or add a new card. <br>";
            return;
        }

        $newBalance = $addBalance + $userBalance; 

        $query = "UPDATE users SET user_balance = '$newBalance' WHERE user_name = '$username'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }else{
            echo "successfully added $addBalance to your account! New balance = $newBalance";
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

        $query = "SELECT g.game_title, i.item_name, i.item_description, i.item_quantity, i.item_id 
                  FROM item_belongsto i, market_item m, game g 
                  WHERE i.item_id = m.item_id and m.user_id = '$userId' and i.game_id = g.game_id";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function getPersonalListings() {
        global $userId, $connection;

        $query = "SELECT l.id, l.market_item_id, i.item_name, l.listed_date, l.listed_price, l.quantity 
                  FROM item_belongsTo i, market_item m, listing l 
                  WHERE i.item_id = m.item_id and m.item_id = l.market_item_id and m.user_id = '$userId' and l.user_id = '$userId'";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }


    function printBillingInfo($result) {
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->CREDITCARD_NUM . "</td>
                      <td>" . $row->EXPIRY_DATE . "</td>
                      <td>" . $row->CVV . "</td>
                      <td>" . $row->CARDHOLDER_NAME . "</td>
                      <td>" . $row->ADDRESS . "</td>
                      <td>" . $row->PHONE_NUMBER . "</td>
                  </tr>";        
        }
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


    function printPersonalListings($result) { //prints results from a select statement
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->ID . "</td>
                      <td>" . $row->MARKET_ITEM_ID . "</td>
                      <td>" . $row->ITEM_NAME . "</td>
                      <td>" . $row->LISTED_DATE . "</td>
                      <td>" . $row->LISTED_PRICE . "</td>
                      <td>" . $row->QUANTITY . "</td>
                  </tr>";         
        }
    }

    /*
    *   Add an item to sell 
    */

    if(isset($_POST['sellSubmit'])){
        $itemName =  $_POST["ItemNameSell"];
        $quantity = $_POST["Quantity"];
        $price = $_POST["Price"];
        getUserInfo();
        if($itemName == ''){
            echo "Adding Listing: No item name specified";
            return;
        }

        if($quantity == ''){
            echo "Adding Listing: No quantity specified";
            return;
        }

        if($price == ''){
            echo "Adding Listing: No price specified";
            return;
        }

        sellSubmit($itemName, $quantity, $price);
    }

    function userHasGameItem($itemName){
        $flag = 'false';
        $result = getItemsInventory();
        while (($row = oci_fetch_object($result)) != False) {
            $useritem = $row->ITEM_NAME;
                if($useritem == $itemName){
                    $flag = 'true';
                    return $flag;
            }
        }
        return $flag;
    }

    function sellSubmit($itemName, $quantity, $price){
        global $userId, $connection;

        if(userHasGameItem($itemName) == 'false'){
            echo "You do not have this game item";
            return;
        }

        $result = getItemsInventory();
        while (($row = oci_fetch_object($result)) != False) {
            $useritem = $row->ITEM_NAME;
                if($useritem == $itemName){
                    $itemId = $row->ITEM_ID;
            }
        }

        $date = date('d-M-Y');

        //$listingID = fun();

        $query = "INSERT INTO listing VALUES ($listingID, $itemId, $userId, to_date('$date','DD-Mon-YYYY'), $price, $quantity)";
        $statement = oci_parse($connection, $query);

        echo $query;

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }else{
            echo "successfully added ($itemId, $userId, $date, $price, $quantity)";
        }



        //TODO: delete item from inventory

    }

    if(isset($_POST['sellUpdateSubmit'])){
        $listingID=  $_POST["ListingIDUpdate"];
        $price = $_POST["PriceUpdate"];
        getUserInfo();
        if($listingID == ''){
            echo "Updating Listing: No listing ID specified";
            return;
        }

        if($quantity == ''){
            echo "Updating Listing: No quantity specified";
            return;
        }
        updateListing($listingID, $price);
    }
    
    function updateListing($listingID, $price){
        global $userId, $connection;
        $query = "UPDATE listing SET listed_price = '$price' where user_id = '$userId' and listing_id = '$listingID'";
        $statement = oci_parse($connection, $query);
    }


    function isAdmin() {
        global $username, $userId, $connection;
        $query = "SELECT administrator_id FROM administrator 
            WHERE '$userId' = administrator_id";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        else {
            $result = oci_fetch_object($statement);
            if ($result != False) {
                echo "<form method=\"POST\" action=";
                echo "\"admin.php?user=$username\">";
                echo "<div id=\"adminButton\" class=\"container\">
                    <input type=\"submit\" value=\"Admin Page\" name=\"adminPage\">
                    </div>
                </form>";
            }
        }
    }


    if ($connection) {
        getUserInfo();
    }

    if(isset($_POST['balanceSubmit'])){
        $addBalance =  $_POST["BalanceAmount"];
        $creditcard = $_POST["CCNumBalance"];
        getUserInfo();
        addBalance($addBalance, $creditcard);
    }

    //insert new CC
    if (isset($_POST['billingInsert'])) {
        $cardNum = $_POST['CCNum'];
        $expiryDate = $_POST['expiryDate'];
        $CVV = $_POST['CVV'];
        $name = $_POST['CCName'];
        $address = $_POST['Address'];
        $phoneNum = $_POST['CCPhoneNum'];

        $query = "INSERT INTO billing_info
                  VALUES ('$cardNum', '$expiryDate', '$CVV', '$name', '$address', '$phoneNum', '$userId')";

        $statement = oci_parse($connection, $query);
        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
    }

    //update CC
    if (isset($_POST['billingUpdate'])) {
        $cardNum = $_POST['CCNum'];
        $expiryDate = $_POST['expiryDate'];
        $CVV = $_POST['CVV'];
        $name = $_POST['CCName'];
        $address = $_POST['Address'];
        $phoneNum = $_POST['CCPhoneNum'];
        
        $query = "select * from billing_info where '$cardNum' = creditcard_num";
        $statement = oci_parse($connection, $query);
        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        if (!oci_fetch_object($statement)) {
            echo "Card number does not match any cards on file.";
        }
        else {
            $query = "UPDATE billing_info
                    SET expiry_date = '$expiryDate', cvv = '$CVV', cardholder_name = '$name', address = '$address', phone_number = '$phoneNum', user_id = '$userId'
                    WHERE creditcard_num = '$cardNum'";

            $statement = oci_parse($connection, $query);
            if (!oci_execute($statement)) {
                $error = oci_error($statement);
                echo htmlentities($error['message']);
            }
        }
    }

    // delete CC
    if (isset($_POST['billingDelete'])) {
        $cardNum = $_POST['CCNum'];
        $query = "select * from billing_info where '$cardNum' = creditcard_num";
        $statement = oci_parse($connection, $query);
        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        if (!oci_fetch_object($statement)) {
            echo "Card number does not match any cards on file.";
        }
        else {
            $query = "DELETE FROM billing_info
                    WHERE creditcard_num = '$cardNum'";

            $statement = oci_parse($connection, $query);
            if (!oci_execute($statement)) {
                $error = oci_error($statement);
                echo htmlentities($error['message']);
            }
        }
    }


?>

<h3>User Info </h3>
<?php isAdmin() ?>
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

<h3>Purchase History </h3>

<table>
<?php 
    $result = getBuyerTransactions();
    printBuyerTransactions($result); 
?>
<br>
<br>
<h3>Sales History </h3>
<?php
    $result = getSellerTransactions();
    printSellerTransactions($result); 
?>
<br>
<br>

<h3>Add Balance</h3>
<form method="POST" action="<?php echo "user.php?user=$username" ?>" >
    <div class="container">
        <label for="CCNumBalance">Credit Card Number</label>
        <input type="text" placeholder="Must match a card on file" name="CCNumBalance" size="25" required>
        <br>
        <label for="BalanceAmount">Amount to Add</label>
        <input type="text" placeholder="$" pattern="(([1-9]\d{0,2}(,\d{3})*)|(([1-9]\d*)?\d))(\.\d\d)?" name="BalanceAmount" required>
        <br>
        <input type="submit" value="Submit" name="balanceSubmit">
    </div>
</form>
<br>

<h3>Billing</h3>
<table style="width:80%">
    <tr>
        <th>Credit Card Number</th>
        <th>Expiry Date</th>
        <th>CVV</th>
        <th>Cardholder Name</th>
        <th>Address</th>
        <th>Phone Number</th>
    </tr>
    <?php
        $result = getBillingInfo();
        printBillingInfo($result);
    ?>
</table>
<br>
<br>

<p><b>Add a new credit card.</b></p>
<form method="POST" action="<?php echo "user.php?user=$username" ?>">
    <div class="container">
        <label for="CCNum">Credit Card Number</label>
        <input type="text" placeholder="Credit Card Number" name="CCNum" maxlength="16" pattern="\d{16}" required>
        <br>
        <label for="CCExpDate">Expiry Date</label>
        <input type="text" placeholder="MMYY" name="expiryDate" maxlength="4" size="6" pattern="(1[0-2]|0[1-9])(1[8-9]|2[0-9])" required>
        <br>
        <label for="CVV">CVV</label>
        <input type="text" placeholder="CVV" name="CVV" maxlength="3" size="4" pattern="\d{3}" required>
        <br>
        <label for="CCName">Cardholder Name</label>
        <input type="text" placeholder="Name" name="CCName" required>
        <br>
        <label for="Address">Address</label>
        <input type="text" placeholder="Address" name="Address" required>
        <br>
        <label for="CCPhoneNum">Phone Number</label>
        <input type="text" placeholder="10-digit number" name="CCPhoneNum" pattern="\d{10}" required>
        <br>
        <input type="submit" value="Insert" name="billingInsert">
    </div>
</form>
<p><b>Update an existing credit card.</b></p>
<form method="POST" action="<?php echo "user.php?user=$username" ?>">
    <div class="container">
        <label for="CCNum">Credit Card Number</label>
        <input type="text" placeholder="Credit Card Number" name="CCNum" maxlength="16" pattern="\d{16}" required>
        <br>
        <label for="CCExpDate">Expiry Date</label>
        <input type="text" placeholder="MMYY" name="expiryDate" maxlength="4" size="6" pattern="(1[0-2]|0[1-9])(1[8-9]|2[0-9])" required>
        <br>
        <label for="CVV">CVV</label>
        <input type="text" placeholder="CVV" name="CVV" maxlength="3" size="4" pattern="\d{3}" required>
        <br>
        <label for="CCName">Cardholder Name</label>
        <input type="text" placeholder="Name" name="CCName" required>
        <br>
        <label for="Address">Address</label>
        <input type="text" placeholder="Address" name="Address" required>
        <br>
        <label for="CCPhoneNum">Phone Number</label>
        <input type="text" placeholder="10-digit number" name="CCPhoneNum" pattern="\d{10}" required>
        <br>
        <input type="submit" value="Update" name="billingUpdate">
    </div>
</form>
<p><b>Delete an existing credit card.</b></p>
<form method="POST" action="<?php echo "user.php?user=$username" ?>">
    <div class="container">
        <label for="CCNum">Credit Card Number</label>
        <input type="text" placeholder="Credit Card Number" name="CCNum" maxlength="16" pattern="\d{16}" required>
        <br>
        <input type="submit" value="Delete" name="billingDelete">
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
        getUserInfo();
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
        getUserInfo();
        $result = getItemsInventory();
        printItemsInventory($result);
    ?>
</table>
<h1>Personal Listings</h1>
<table>
    <tr>
        <th>Listing ID</th>
        <th>Market Item ID</th>
        <th>Item Name</th>
        <th>Listed Date</th>
        <th>Listed Price</th>
        <th>Quantity</th>
    </tr>
    <?php
        getUserInfo();
        $result = getPersonalListings();
        printPersonalListings($result);
    ?>
</table>
<p>Select an item Name and sell the item.</p>
<form method="POST" action="<?php echo "user.php?user=$username" ?>">
    <div class="container">
        <label for="ItemNameSell">Item Name</label>
        <input type="text" placeholder="Item Name" name="ItemNameSell" required>
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
<p>Update an existing listing.</p>
<form method="POST" action="<?php echo "user.php?user=$username" ?>">
    <div class="container">
        <label for="ListingIDUpdate">Listing ID</label>
        <input type="text" placeholder="Listing ID" name="ListingIDUpdate" required>
        <br>
        <label for="PriceUpdate">Price</label>
        <input type="text" placeholder="Price" name="PriceUpdate" required>
        <br>
        <input type="submit" value="Update" name="sellUpdateSubmit">
    </div>
</form>
<p>Remove an item Listing.</p>
<form method="POST" action="<?php echo "user.php?user=$username" ?>">
    <div class="container">
        <label for="ListingIDRemove">Listing ID</label>
        <input type="text" placeholder="Listing ID" name="ListingIDRemove" required>
        <br>
    </div>
</form>
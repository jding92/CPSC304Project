<?php
    $username = $_GET['user'];
    $connection = oci_connect("ora_z2p0b", "a48540158", "dbhost.ugrad.cs.ubc.ca:1522/ug");

    function getUserInfo() {
        global $userId, $username, $userEmail, $userBalance, $userTransactions, $connection;

        $query = "SELECT user_id, user_balance FROM users WHERE user_name = '$username'";
        $statement = oci_parse($connection, $query);
        
        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        else {
            $result = oci_fetch_object($statement);
            $userId = $result->USER_ID;
            $userBalance = $result->USER_BALANCE;
        }      
    }

    function getListingInfo() {
        global $connection;

        $query = "SELECT s.user_name, market_item_id,  from listing";
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

    if ($connection) {
        getUserInfo();
        
    }
?>

<h1>Listings</h1>
<table>
    <tr>
        <th>Seller ID</th>
        <th>Market Item ID</th>
        <th>Item Name</th>
        <th>Game Title</th>
        <th>Listed Date</th>
        <th>Listed Price</th>
        <th>Quantity</th>
    </tr>
</table>
<h2>Filters</h2>
<!-- Selection/projection query -->
Find the items within the price range, and return the selected columns.
<form method="POST" action="listings.php">
    <div class="container">
        <label for="minPrice">Minimum Price</label>
        <input type="text" placeholder="Minimum Price" name="minPrice" required>
        <br>
        <label for="maxPrice">Maximum Price</label>
        <input type="text" placeholder="Maximum Price" name="maxPrice" required>
        <br>
        <label for="attribute">Attributes to Display</label>
        <br>
        <input type="checkbox" name="attribute" value="user_id">Seller ID
        <br>
        <input type="checkbox" name="attribute" value="market_item_id">Market Item ID
        <br>
        <input type="checkbox" name="attribute" value="item_name">Item Name
        <br>
        <input type="checkbox" name="attribute" value="game_title">Game Title
        <br>
        <input type="checkbox" name="attribute" value="listed_date">Listed Date
        <br>
        <input type="checkbox" name="attribute" value="listed_price">Listed Price
        <br>
        <input type="checkbox" name="attribute" value="quantity">Quantity
        <br>
        <input type="submit" value="Filter" name="filterSubmit">
    </div>
</form>
<!-- Aggregation query -->
Find the item(s) with the minimum or maximum price.
<form method="POST" action="listings.php">
    <div class="container">
        <input type="submit" value="Minimum" name="minPriceSubmit">
        <input type="submit" value="Maximum" name="maxPriceSubmit">
    </div>
</form>
<!-- Nested aggregation with group-by -->
<h2>Min/Max Average Item Price per Game</h2>
<table>
    <tr>
        <th>Game Title</th>
        <th>Average Item Price</th>
    </tr>
</table>
Find the average item price for each Game, and then return the minimum or maximum across those averages.
<form method="POST" action="listings.php">
    <div class="container">
        <input type="submit" value="Minimum" name="minAverageSubmit">
        <input type="submit" value="Maximum" name="maxAverageSubmit">
    </div>
</form>
<h2>Buy Item</h2> Select the Seller ID and Item ID to buy the item.
<form method="POST" action="listings.php">
    <div class="container">
        <label for="SellerID">Seller ID</label>
        <input type="text" placeholder="Seller ID" name="SellerID" required>
        <br>
        <label for="ItemIDBuy">Item ID</label>
        <input type="text" placeholder="Item ID" name="ItemIDBuy" required>
        <br>
        <input type="submit" value="Buy" name="buySubmit">
    </div>
</form>
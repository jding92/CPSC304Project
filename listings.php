<?php
    $username = $_GET['user'];
    $connection = oci_connect("ora_z8b0b", "a16381139", "dbhost.ugrad.cs.ubc.ca:1522/ug");

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

        $query = "SELECT l.id, s.user_name, i.item_name, g.game_title, l.listed_date, l.listed_price, l.quantity 
                  FROM listing l 
                  INNER JOIN users s ON s.user_id = l.user_id 
                  INNER JOIN item_belongsto i ON i.item_id = l.market_item_id 
                  INNER JOIN game g ON i.game_id = g.game_id";
        $statement = oci_parse($connection, $query);
        
        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function printListingInfo($result) {
        while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->ID . "</td>
                      <td>" . $row->USER_NAME . "</td>
                      <td>" . $row->ITEM_NAME . "</td>
                      <td>" . $row->GAME_TITLE . "</td>
                      <td>" . $row->LISTED_DATE . "</td>
                      <td>" . "$" . number_format((float)$row->LISTED_PRICE, 2, '.', '') . "</td>
                      <td>" . $row->QUANTITY . "</td>
                  </tr>";        
        }
    }

    function getMinAverage() {
        global $connection;

        // $query = "SELECT MIN(x.avg) AS MinAvgPrice
        //     FROM (
        //         SELECT AVG(listed_price) as avg FROM listing, item_belongsTo WHERE listing.market_item_id = item_belongsTo.item_id GROUP BY item_belongsTo.game_id
        //     ) x";
        $query = "SELECT * FROM listing";
        $statement = oci_parse($connection, $query);

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
            echo "bye";
          } else {
            echo "<table>
                  <tr>
                    <th>Minimum Average Price</th>
                  </tr>";

            while (($row = oci_fetch_object($statement) != False)) {
                echo "HI";
                echo "<tr><td>" . $row->ID . "</td>
                      </tr>";        
                echo "</table>";
            }
          }
    }

    function nestedAggButtons() {
      global $username;
      echo "<form method=\"POST\" action=";
      echo "\"listings.php?user=$username\">";
      echo "<div class=\"container\">
                <input type=\"submit\" value=\"Minimum\" name=\"minAverageSubmit\">
                <input type=\"submit\" value=\"Maximum\" name=\"maxAverageSubmit\">
            </div>
        </form>";
    }

    if ($connection) {
        getUserInfo();
        
        if (array_key_exists('minAverageSubmit', $_POST)) {
            getMinAverage();
        }
    }
?>
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

<h3>Listings</h3>
<table>
    <tr>
        <th>Listing ID</th>
        <th>Seller Name</th>
        <th>Item Name</th>
        <th>Game Title</th>
        <th>Listed Date</th>
        <th>Listed Price</th>
        <th>Quantity</th>
    </tr>
    <?php 
        $result = getListingInfo();
        printListingInfo($result);
    ?>
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
<?php nestedAggButtons() ?>
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
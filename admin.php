<?php 
    session_start();
    $userId = isset($userId) ? $userId : '';
    $username = $_GET['user'];
    $userEmail = isset($userEmail) ? $userEmail : '';
    $userBalance = isset($userBalance) ? $userBalance : '';
    $userTransactions = isset($userTransactions) ? $userTransactions : '';
    $connection = oci_connect("ora_z8b0b", "a16381139", "dbhost.ugrad.cs.ubc.ca:1522/ug");

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

    function getListingsMonitored() {
      global $userId, $connection;
      $query = "SELECT user_id, market_item_id FROM monitors
        WHERE administrator_id = '$userId'";
      $statement = oci_parse($connection, $query);

      if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function printListingsMonitored($result) {
      echo "<table>
              <tr>
                <th>User ID</th>
                <th>Market Item ID</th>
              </tr>";

      while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->USER_ID . "</td>
                      <td>" . $row->MARKET_ITEM_ID . "</td>
                  </tr>";        
      }
      echo "</table>";

    }

    function getTransactionsSupervised() {
      global $userId, $connection;
      $query = "SELECT transaction_id, purchase_date, purchase_price, creditcard_num, buyer_id, seller_id, market_item_id FROM transaction_supervises
        WHERE administrator_id = '$userId'";
      $statement = oci_parse($connection, $query);

      if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;

    }

    function printTransactionsSupervised($result) {
       echo "<table>
              <tr>
                <th>Transaction ID</th>
                <th>Purchase Date</th>
                <th>Purchase Price</th>
                <th>Credit Card Number</th>
                <th>Buyer ID</th>
                <th>Seller ID</th>
                <th>Market Item ID</th>
              </tr>";

      while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->TRANSACTION_ID . "</td>
                      <td>" . $row->PURCHASE_DATE . "</td>
                      <td>" . $row->PURCHASE_PRICE . "</td>
                      <td>" . $row->CREDITCARD_NUM . "</td>
                      <td>" . $row->BUYER_ID . "</td>
                      <td>" . $row->SELLER_ID . "</td>
                      <td>" . $row->MARKET_ITEM_ID . "</td>
                  </tr>";        
      }
      echo "</table>";
    }

    function undoTransaction($transactionId) {
      global $connection;
      $query = "SELECT purchase_price, buyer_id, seller_id, market_item_id FROM transaction_supervises WHERE transaction_id = '$transactionId'";
      $statement = oci_parse($connection, $query);

      if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
      } else {
        $row = oci_fetch_object($statement);
        $purchase_price = $row->PURCHASE_PRICE;
        $buyer_id = $row->BUYER_ID;
        $seller_id = $row->SELLER_ID;
        $market_item_id = $row->MARKET_ITEM_ID;

        // refund buyer
        $query1 = "UPDATE users SET user_balance = user_balance + '$purchase_price' WHERE user_id = '$buyer_id'";

        // update market item UID from buyer to seller
        $query2 = "UPDATE market_item SET user_id = '$seller_id' WHERE item_id = '$market_item_id'";

        // delete transaction
        $query3 = "DELETE FROM transaction_supervises WHERE transaction_id = '$transactionId'";

        $statement1 = oci_parse($connection, $query1);
        $statement2 = oci_parse($connection, $query2);
        $statement3 = oci_parse($connection, $query3);
        // oci_execute($statement1);
        // oci_execute($statement2);
        // oci_execute($statement3);
        if (!oci_execute($statement1)) {
            $error = oci_error($statement1);
            echo htmlentities($error['message']);
        } else if (!oci_execute($statement2)){
            $error = oci_error($statement2);
            echo htmlentities($error['message']);
        } else if (!oci_execute($statement3)){
            $error = oci_error($statement3);
            echo htmlentities($error['message']);
        }
      }
    }

    function transactionUndoButton() {
      global $username;
      echo "<form method=\"POST\" action=";
      echo "\"admin.php?user=$username\">";
      echo "<div class=\"container\">
              <label for=\"TransactionID\">Transaction ID</label>
              <input type=\"text\" placeholder=\"Transaction ID\" name=\"TransactionID\" required>
              <br>
              <input type=\"submit\" value=\"Undo\" name=\"transactionUndo\">
          </div>
      </form>";
    }

    function deleteGame($gameID) {
      global $connection;
      $query = "DELETE FROM game WHERE game_id='$gameID'";
      $statement = oci_parse($connection, $query);
      if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
      }
    }

    function deleteGameButton() {
      global $username;
      echo "<form method=\"POST\" action=";
      echo "\"admin.php?user=$username\">";
      echo "<div class=\"container\">
              <label for=\"gameIDDelete\">Game ID</label>
              <input type=\"text\" placeholder=\"Game ID\" name=\"gameIDDelete\" required>
              <br>
              <input type=\"submit\" value=\"Delete\" name=\"gameDelete\">
          </div>
      </form>";
    }

    function getGames() {
      global $connection;
      $query = "SELECT game_id, game_title FROM game";
      $statement = oci_parse($connection, $query);

      if (!oci_execute($statement)) {
            $error = oci_error($statement);
            echo htmlentities($error['message']);
        }
        return $statement;
    }

    function printGames($result) {
      echo "<table>
              <tr>
                <th>Game ID</th>
                <th>Game Title</th>
              </tr>";

      while (($row = oci_fetch_object($result)) != False) {
            echo "<tr><td>" . $row->GAME_ID . "</td>
                      <td>" . $row->GAME_TITLE . "</td>
                  </tr>";        
      }
      echo "</table>";
    }

    if ($connection) {
      getUserInfo();

      if (array_key_exists('transactionUndo', $_POST)) {
        $transactionId = $_POST['TransactionID'];
        undoTransaction($transactionId);
      } else if (array_key_exists('gameDelete', $_POST)) {
        $gameId = $_POST['gameIDDelete'];
        deleteGame($gameId);
      }
    }
?>

<h1>Listings Monitored</h1>
<?php
  $result = getListingsMonitored();
  printListingsMonitored($result);
?>
<h1>Transactions Supervised</h1>
<?php
  $result = getTransactionsSupervised();
  printTransactionsSupervised($result);
?>
<p>Input a transaction ID to revert the transaction.</p>
<?php transactionUndoButton() ?>
<h1>Games</h1>
<?php
  $result = getGames();
  printGames($result);
?>
<!-- Deletion query -->
<p>Delete a game from the system.</p>
<?php deleteGameButton(); ?>
<!-- Division query -->
Find the user IDs who have bought all the items for Skyrim.
<table>
    <tr>
        <th>User ID</th>
    </tr>
</table>
<form method="POST" action="admin.php">
    <div class="container">
        <input type="submit" value="Submit" name="divisionSubmit">
    </div>
</form>
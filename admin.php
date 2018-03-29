<?php 
    session_start();
    $userId = isset($userId) ? $userId : '';
    // $username = $_GET['user'];
    // $userEmail = isset($userEmail) ? $userEmail : '';
    // $userBalance = isset($userBalance) ? $userBalance : '';
    // $userTransactions = isset($userTransactions) ? $userTransactions : '';
    $connection = oci_connect("ora_z8b0b", "a16381139", "dbhost.ugrad.cs.ubc.ca:1522/ug");

    function getListingsMonitored() {
      $query = "SELECT user_id, market_item_id FROM monitors
        WHERE administrator_id = '$userId";
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

    }

    function printTransactionsSupervised() {

    }

    if ($connection) {
      
    }
?>

<h1>Listings Monitored</h1>
<?php
  $result = getListingsMonitored();
  printListingsMonitored($result);
?>
<h1>Transactions Supervised</h1>
<table>
    <tr>
        <th>Transaction ID</th>
        <th>Purchase Date</th>
        <th>Purchase Price</th>
        <th>Credit Card Number</th>
        <th>Buyer ID</th>
        <th>Seller ID</th>
        <th>Market Item ID</th>
    </tr>
</table>
<p>Input a transaction ID to revert the transaction.</p>
<form method="POST" action="admin.php">
    <div class="container">
        <label for="TransactionID">Transaction ID</label>
        <input type="text" placeholder="Transaction ID" name="TransactionID" required>
        <br>
        <input type="submit" value="Undo" name="transactionUndo">
    </div>
</form>
<h1>Games</h1>
<table>
    <tr>
        <th>Game ID</th>
        <th>Game Title</th>
    </tr>
</table>
<!-- Deletion query -->
<p>Delete a game from the system.</p>
<form method="POST" action="admin.php">
    <div class="container">
        <label for="gameIDDelete">Game ID</label>
        <input type="text" placeholder="Game ID" name="gameIDDelete" required>
        <br>
        <input type="submit" value="Delete" name="gameDelete">
    </div>
</form>
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
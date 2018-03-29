<h1>User Info</h1>
<h2>ID: </h2>
<h2>Name: </h2>
<h2>Email: </h2>
<h2>Balance: </h2>
<h2>Previous Transactions: </h2>
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
<h1>Add Balance</h1>
<form method="POST" action="user.php">
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
<h1>Game Inventory</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
    </tr>
</table>
<h1>Item Inventory</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Game ID</th>
        <th>Game Title</th>
    </tr>
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
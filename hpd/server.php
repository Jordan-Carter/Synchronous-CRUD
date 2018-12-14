<?php
session_start();

//connect to the database
$db = 'hpd';
$username = 'root';
$password = '';
$conn = mysqli_connect('localhost', $username, $password, $db);

//retreive results
if(isset($_GET['filter'])){
    $searchResult = $_GET['filter'];
    $results = mysqli_query($conn, "SELECT * FROM owners WHERE fname LIKE '%$searchResult%' OR lname LIKE '%$searchResult%'");
}
else{
    $results = mysqli_query($conn, 'SELECT * FROM owners');
}

//pagination
$numberOfResults = mysqli_num_rows($results);
if(isset($_GET['perPage'])){
    $resultsPerPage = $_GET['perPage'];
}
else{
    $resultsPerPage = 10;
}
$numberOfPages = ceil($numberOfResults/$resultsPerPage);

if(!isset($_GET['currentPage'])){
    $currentPage = 1;
}
else{
    $currentPage = $_GET['currentPage'];
}

$pageStartingPoint = ($currentPage - 1) * $resultsPerPage;
if(isset($_GET['filter'])){
$query = "SELECT * FROM owners WHERE fname LIKE '%$searchResult%' OR lname LIKE '%$searchResult%' LIMIT $pageStartingPoint , $resultsPerPage";
}
else{
$query = "SELECT * FROM owners LIMIT $pageStartingPoint , $resultsPerPage";
}
$filteredResults = mysqli_query($conn, $query);


//get info for ownerItems modal
if(isset($_GET['openModal'])){
    $currentId = $_GET['id'];
    $currentRec = mysqli_query($conn, "SELECT * FROM owners WHERE id=$currentId");
    $currentRecord = mysqli_fetch_array($currentRec);
    $firstName = $currentRecord['fname'];
    $lastName = $currentRecord['lname'];
    $itemsKey = mysqli_query($conn, "SELECT * FROM ownerItems WHERE ownerKey=$currentId");
}

//add a new owner to the owners table
if(isset($_POST['save'])){
    $first = $_POST['first'];
    $last = $_POST['last'];
    $adOne = $_POST['adOne'];
    $adTwo = $_POST['adTwo'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $policy = $_POST['policy'];
    $expiration = $_POST['expiration'];

    $ownerQuery = "INSERT INTO owners (fname, lname, street1, street2, city, state, zip, policy, expiration) VALUES ('$first', '$last', '$adOne', '$adTwo', '$city', '$state', '$zip', '$policy', '$expiration')";
    mysqli_query($conn, $ownerQuery);
    header('Location: '.$_SERVER['REQUEST_URI']);
}

//add a new item to the items table and link it to the owner
if(isset($_POST['saveItem'])){
    $myid = $_POST['saveItem'];
    $itemName = $_POST['itemName'];
    $itemPhoto = $_POST['itemPhoto'];
    $itemDescription = $_POST['itemDescription'];
    $itemValuation = $_POST['itemValuation'];
    $itemMethod = $_POST['itemMethod'];
    $itemDate = $_POST['itemDate'];

    $itemQuery = "INSERT INTO items (name, photo, description, valuation, method, creationDate) VALUES ('$itemName', '$itemPhoto', '$itemDescription', '$itemValuation', '$itemMethod', '$itemDate')";
    mysqli_query($conn, $itemQuery);
    $currentItem = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC LIMIT 1");
    $record = mysqli_fetch_array($currentItem);
    $itemId = $record['id'];
    mysqli_query($conn, "INSERT INTO ownerItems (ownerKey, itemKey) VALUES ('$myid', '$itemId')");

    header('Location: '.$_SERVER['REQUEST_URI']);
}

//get owner to edit
if(isset($_GET['edit'])){
    $edit = $_GET['edit'];
    $rec = mysqli_query($conn, "SELECT * FROM owners WHERE id=$edit");
    $record = mysqli_fetch_array($rec);
    $first = $record['fname'];
    $last = $record['lname'];
    $adOne = $record['street1'];
    $adTwo = $record['street2'];
    $city = $record['city'];
    $state = $record['state'];
    $zip = $record['zip'];
    $policy = $record['policy'];
    $expiration = $record['expiration'];
}

//get item to edit
if(isset($_GET['editItem'])){
    $itemEdit = $_GET['editItem'];
    $rec = mysqli_query($conn, "SELECT * FROM items WHERE id=$itemEdit");
    $record = mysqli_fetch_array($rec);
    $editName = $record['name'];
    $editPhoto = $record['photo'];
    $editDescription = $record['description'];
    $editValuation = $record['valuation'];
    $editMethod = $record['method'];
    $editVerified = $record['verified'];
    $editDate = $record['creationDate'];
}

//update owner
if(isset($_POST['updateOwner'])){
    $edit = $_POST['updateOwner'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $adOne = $_POST['adOne'];
    $adTwo = $_POST['adTwo'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $policy = $_POST['policy'];
    $expiration = $_POST['expiration'];

    mysqli_query($conn, "UPDATE owners SET fname='$first', lname='$last', street1='$adOne', street2='$adTwo', city='$city', state='$state', zip='$zip', policy='$policy', expiration='$expiration' WHERE id=$edit");
    header('Location: '.$_SERVER['REQUEST_URI']);
}

//update item
if(isset($_POST['updateItem'])){
    $myid = $_POST['updateItem'];
    $itemName = $_POST['itemName'];
    $itemPhoto = $_POST['itemPhoto'];
    $itemDescription = $_POST['itemDescription'];
    $itemValuation = $_POST['itemValuation'];
    $itemMethod = $_POST['itemMethod'];
    $itemDate = $_POST['itemDate'];

    mysqli_query($conn, "UPDATE items SET photo='$itemPhoto', name='$itemName', description='$itemDescription', valuation='$itemValuation', method='$itemMethod', creationDate='$itemDate' WHERE id=$myid");
    header('Location: '.$_SERVER['REQUEST_URI']);
}

//delete an owner
if(isset($_GET['delOwner'])){
    $id = $_GET['delOwner'];
    mysqli_query($conn, "DELETE FROM owners WHERE id=$id");
    $_SESSION['msg'] = "Record Deleted";
    header('location: hpd.php');
}

//delete a connection to an item
if(isset($_GET['delOwner'])){
    $id = $_GET['delOwner'];
    mysqli_query($conn, "DELETE FROM ownerItems WHERE itemKey=$id");
    header('location: hpd.php');
}

//FOR REFERENCE ONLY
//DO NOT UNCOMMENT!!!!!
//create owneritems table
//for every owner in the owners table insert that owners id
/*for ($i = 1; $i <= 4000; $i++)
{
//insert a random number id ranging from the first id in items to the last id in items one to four times
$owner = rand(1, 4000);
$id = $i;
$item = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
//using the items id insert all of the info into the table
$itemInfo = mysqli_fetch_array($item);
$name = $itemInfo['name'];
$photo = $itemInfo['photo'];
$description = $itemInfo['description'];
$valuation = $itemInfo['valuation'];
$method = $itemInfo['method'];
$verified = $itemInfo['verified'];
$date = $itemInfo['creationDate'];

mysqli_query($conn, "INSERT INTO owneritems (ownerKey, itemKey, itemName, itemPhoto, description, valuation, method, verified, creationDate) VALUES ('$owner', '$id', '$name', '$photo', '$description', '$valuation', '$method', '$verified', '$date')");
}*/

//Include database connection here

// Run the Query
?>

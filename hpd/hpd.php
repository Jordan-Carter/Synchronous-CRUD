<?php include('server.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HPD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body onload="helper()" class="bg-secondary">

<div class="container my-5">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="hpd.php">Owners</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <button type="button" class="btn btn-success mr-3" data-toggle="modal" data-placement="top" title="Add Owner" data-target="#ownerSubmissionModal">+</button>    
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Items Per Page
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo $_SERVER['REQUEST_URI']; echo "&perPage=5"?>">5</a>
          <a class="dropdown-item" href="<?php echo $_SERVER['REQUEST_URI']; echo "&perPage=10"?>">10</a>
          <a class="dropdown-item" href="<?php echo $_SERVER['REQUEST_URI']; echo "&perPage=20"?>">20</a>
          <a class="dropdown-item" href="<?php echo $_SERVER['REQUEST_URI']; echo "&perPage=50"?>">50</a>
          <a class="dropdown-item" href="<?php echo $_SERVER['REQUEST_URI']; echo "&perPage=100"?>">100</a>
        </div>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
     <?php if(isset($_GET['perPage'])) { ?>
        <input type="hidden" name="perPage" value="<?php echo $_GET['perPage']?>">
     <?php } ?>
      <input name="filter" class="form-control mr-sm-2" type="search" placeholder="Filter Results" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
<table class="table table-responsive table-dark table-striped table-hover">
    <thead>
        <tr>
            <th nowrap><a>First Name</a></th>
            <th nowrap>Last Name</th>
            <th nowrap>Address 1</th>
            <th nowrap>Address 2</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Policy</th>
            <th>Expiration</th>
            <th colspan=2>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_array($filteredResults)) {?>
        <tr class="ownerInfo">
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['fname'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['lname'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['street1'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['street2'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['city'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo strtoupper($row['state']);?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['zip'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['policy'];?></td>
            <td onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'id')"><?php echo $row['expiration'];?></td>
            <td><a onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'edit')" class="btn btn-success" href="hpd.php?edit=<?php echo $row['id']?>&currentPage=<?php echo $currentPage ?>&perPage=<?php echo isset($_GET['perPage']) ? $_GET['perPage']:10; ?><?php if(isset($_GET['filter'])){ echo '&filter='; echo $_GET['filter'];}?>" role=button>Edit</a></td>
            <td><a class="btn btn-danger" href="hpd.php?delOwner=<?php echo $row['id']; ?>" role=button>Delete</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>  

<form method="get" action="hpd.php">
    <nav aria-label="Results">
        <ul class="pagination">
            <?php if(isset($_GET['filter'])) { ?>
                <input type="hidden" name="filter" value="<?php echo $_GET['filter'] ?>">
            <?php } ?>
            <?php if(isset($_GET['perPage'])) { ?>
                <input type="hidden" name="perPage" value="<?php echo $_GET['perPage']?>">
            <?php } ?>
            <?php  
            $limit = 2;
            $previous = $currentPage - 1;
            $next = $currentPage + 1;
            ?>
            <?php if ($currentPage != 1) { ?>
                <li class='page-item'>
                    <button type="submit" class="btn btn-dark" name='currentPage' value=1><strong><<</strong></button>
                </li>
            <?php } ?>
            <?php if ($numberOfPages != 1) { ?>
                <li class='page-item'>
                    <button type="submit" class="btn btn-dark" name='currentPage' value=<?php echo $previous; ?>><strong><</strong></button>
                </li>
            <?php } ?>
            <?php for ($i = max(1, ($currentPage - $limit)); $i <= max($limit * 2 + 1, min(($currentPage + $limit), $numberOfPages)); $i++){ ?>

                <li class='page-item'>
                    <button type="submit" class="btn <?php if ($i == $currentPage) echo "btn-primary"; ?> <?php if ($i != $currentPage) echo "btn-dark"; ?> " name='currentPage' value=<?php echo $i ?>><?php echo $i ?></button>
                </li>
            <?php } ?>
            <?php if ($currentPage != $numberOfPages) { ?>
                <li class='page-item'>
                    <button type="submit" class="btn btn-dark" name='currentPage' value=<?php echo $next ?>><strong>></strong></button>
                </li>
                <li class='page-item'>
                <button type="submit" class="btn btn-dark" name='currentPage' value=<?php echo $numberOfPages; ?>><strong>>></strong></button>
                </li>
            <?php } ?>
        </ul>
    </nav>
</form>
</div>


<div class="modal fade" id="ownerItemsModal" tabindex="-1" role="dialog" aria-labelledby="ownersItemsModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div onclose="unsetModal(<?php echo $currentPage ?>)" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong><?php echo $firstName; echo " "; echo $lastName; ?></strong></h5>
        <button type="button" class="btn btn-success btn-sm mx-3" data-toggle="modal" data-target="#itemSubmissionModal">+</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table class="table table-responsive table-primary table-striped table-hover">
    <thead>
        <tr>
            <th>Item</th>
            <th>Description</th>
            <th>Valuation</th>
            <th>Method</th>
            <th>Verified</th>
            <th nowrap>Creation Date</th>
            <th colspan=2>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while($itemKey = mysqli_fetch_array($itemsKey)){
                $key = $itemKey['itemKey'];
                $items = mysqli_query($conn, "SELECT * FROM items WHERE id=$key");
                $itemRow = mysqli_fetch_array($items);?>
        <tr>
            <td><?php echo $itemRow['name'];?></td>
            <td><?php echo $itemRow['description'];?></td>
            <td><?php echo "$"; echo $itemRow['valuation'];?></td>
            <td><?php echo $itemRow['method'];?></td>
            <td><?php echo $itemRow['verified'] ? "yes":"no";?></td>
            <td><?php echo $itemRow['creationDate'];?></td>
            <td><a onclick="openModal(<?php echo $row['id']?>, <?php echo $currentPage ?>,'editItem')" class="btn btn-success" href="hpd.php?editItem=<?php echo $itemRow['id']?>&currentPage=<?php echo $currentPage ?>&perPage=<?php echo isset($_GET['perPage']) ? $_GET['perPage']:10; ?><?php if(isset($_GET['filter'])){ echo '&filter='; echo $_GET['filter'];}?>" role=button>Edit</a></td>
            <td><a class="btn btn-danger" href="hpd.php?delItem=<?php echo $row['id']; ?>" role=button>Delete</a></td>
            </tr>
    <?php } ?>
    </tbody>
    </table>  
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="ownerSubmissionModal" tabindex="-1" role="dialog" aria-labelledby="ownerSubmissionModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>Add a New Owner</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="hpd.php?currentPage=<?php echo $currentPage ?>">
            <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstName">First Name</label>
                <input name="first" type="text" class="form-control" id="firstName" placeholder="Enter First Name">
            </div>
            <div class="form-group col-md-6">
                <label for="lastName">Last Name</label>
                <input name="last" type="text" class="form-control" id="lastName" placeholder="Enter Last Name">
            </div>
            </div>
            <div class="form-group">
                <label for="addressOne">Address Line 1</label>
                <input name="adOne" type="text" class="form-control" id="adressOne" placeholder="Enter First Address Line">
            </div>
            <div class="form-group">
                <label for="addressTwo">Address Line 2</label>
                <input name="adTwo" type="text" class="form-control" id="addressTwo" placeholder="Enter Second Address Line">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input name="city" type="text" class="form-control" id="city" placeholder="City">
                </div>
                <div class="form-group col-md-4">
                    <label for="state">State</label>
                    <select name="state" class="form-control" id="state">
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District Of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                    </select>				
                
                </div>
                <div class="form-group col-md-2">
                    <label for="zip">Zip Code</label>
                    <input name="zip" type="text" class="form-control" id="zip" placeholder="Zip">
                </div>
            </div>
            <div class="form-group">
                <label for="policy">Policy</label>
                <input name="policy" type="text" class="form-control" id="policy" placeholder="Enter Policy">
            </div>
            <div class="form-group">
                <label for="expiration">Expiration Date</label>
                <input name="expiration" type="text" class="form-control" id="expiration" placeholder="yyyy-dd-mm">
            </div>
        </div>
            
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="save">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemSubmissionModal" tabindex="-1" role="dialog" aria-labelledby="itemSubmissionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemSubmissionModalLabel"><strong>Add a New Item</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="hpd.php?currentPage=<?php echo $currentPage ?>">
            <div class="form-group">
                <label for="itemName">Item Name</label>
                <input name="itemName" type="text" class="form-control" id="itemName" placeholder="Enter Name of Item">
            </div>
            <div class="form-group">
                <label for="itemPhoto">Photo</label>
                <input name="itemPhoto" type="text" class="form-control" id="itemPhoto" placeholder="Enter Link to Photo if Avaliable">
            </div>
            <div class="form-group">
            <div class="form-group">
                <label for="itemDescription">Description</label>
                <textarea name="itemDescription" class="form-control" id="itemDescription" rows="3" placeholder="Write Description of Item Here"></textarea>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                <label for="itemValuation">Valuation Amount</label>
                <input name="itemValuation" type="text" class="form-control" id="itemValuation" placeholder="Enter Items Valuation Amount">
            </div>
            <div class="form-group col-md-6">
                <label for="itemMethod">Method</label>
                <input name="itemMethod" type="text" class="form-control" id="itemMethod" placeholder="Enter Method of Valuation">
            </div>
            </div>
            <p>Verified</p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="itemValidated" id="itemValidated" value=1>
                <label class="form-check-label" for="exampleRadios1">
                    Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="itemValidated" id="itemValidated" value=0 checked>
                <label class="form-check-label" for="exampleRadios2">
                    No
                </label>
            </div>
            <br>
            <div class="form-group">
                <label for="itemDate">Creation Date</label>
                <input name="itemDate" type="text" class="form-control" id="itemDate" placeholder="yyyy-dd-mm">
            </div>
        </div>
            
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="saveItem" value=<?php echo $_GET['id'] ?>>Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel"><strong><?php echo $first; echo " "; echo $last;?></strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="hpd.php?currentPage=<?php echo $currentPage ?>">
            <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstName">First Name</label>
                <input value=<?php echo $first ?> name="first" type="text" class="form-control" id="firstName">
            </div>
            <div class="form-group col-md-6">
                <label for="lastName">Last Name</label>
                <input value=<?php echo $last ?> name="last" type="text" class="form-control" id="lastName">
            </div>
            </div>
            <div class="form-group">
                <label for="addressOne">Address Line 1</label>
                <input value=<?php echo $adOne?> name="adOne" type="text" class="form-control" id="adressOne">
            </div>
            <div class="form-group">
                <label for="addressTwo">Address Line 2</label>
                <input value=<?php echo $adTwo ?> name="adTwo" type="text" class="form-control" id="addressTwo">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City</label>
                    <input value=<?php echo $city ?> name="city" type="text" class="form-control" id="city">
                </div>
                <div class="form-group col-md-4">
                    <label for="state">State</label>
                    <select value=<?php echo strtoupper($state) ?> name="state" class="form-control" id="state">
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District Of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                    </select>				
                
                </div>
                <div class="form-group col-md-2">
                    <label for="zip">Zip Code</label>
                    <input value=<?php echo $zip ?> name="zip" type="text" class="form-control" id="zip">
                </div>
            </div>
            <div class="form-group">
                <label for="policy">Policy</label>
                <input value=<?php echo $policy ?> name="policy" type="text" class="form-control" id="policy">
            </div>
            <div class="form-group">
                <label for="expiration">Expiration Date</label>
                <input value=<?php echo $expiration ?> name="expiration" type="text" class="form-control" id="expiration">
            </div>
        </div>
            
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="updateOwner" value=<?php echo $edit ?>>Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="itemEditModal" tabindex="-1" role="dialog" aria-labelledby="itemEditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemEditModalLabel"><strong><?php echo $editName ?></strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="hpd.php?currentPage=<?php echo $currentPage ?>">
            <div class="form-group">
                <label for="itemName">Item Name</label>
                <input value=<?php echo $editName ?> name="itemName" type="text" class="form-control" id="itemName" placeholder="Enter Name of Item">
            </div>
            <div class="form-group">
                <label for="itemPhoto">Photo</label>
                <input value=<?php echo $editPhoto ?> name="itemPhoto" type="text" class="form-control" id="itemPhoto" placeholder="Enter Link to Photo if Avaliable">
            </div>
            <div class="form-group">
            <div class="form-group">
                <label for="itemDescription">Description</label>
                <textarea name="itemDescription" class="form-control" id="itemDescription" rows="3" placeholder="Write Description of Item Here"><?php echo $editDescription ?></textarea>
            </div>
            <div class='form-row'>
            <div class="form-group col-md-6">
                <label for="itemValuation">Valuation Amount</label>
                <input value=<?php echo $editValuation?> name="itemValuation" type="text" class="form-control" id="itemValuation" placeholder="Enter Items Valuation Amount">
            </div>
            <div class="form-group col-md-6">
                <label for="itemMethod">Method</label>
                <input value=<?php echo $editMethod?> name="itemMethod" type="text" class="form-control" id="itemMethod" placeholder="Enter Method of Valuation">
            </div>
            </div>
            <p>Verified</p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="itemValuation" id="itemValuation" value=1 <?php if($editVerified = 1) { echo "checked"; } ?>>
                <label class="form-check-label" for="exampleRadios1">
                    Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="itemValuation" id="itemValuation" value=0 <?php if($editVerified = 0) { echo "checked"; } ?>>
                <label class="form-check-label" for="exampleRadios2">
                    No
                </label>
            </div>
            <br>
            <div class="form-group">
                <label for="itemDate">Creation Date</label>
                <input value=<?php echo $editDate?> name="itemDate" type="text" class="form-control" id="itemDate" placeholder="Enter Creation Date">
            </div>
        </div>
            
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" name="updateItem" value=<?php echo $_GET['editItem'] ?>>Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
        </div>
    </div>
</div>

<script>
    function openModal(clickedId, page, value){
        
        perPage = <?php echo isset($_GET['perPage']) ? $_GET['perPage']:10; ?>;
        myfilter = "<?php if(isset($_GET['filter'])) echo $_GET['filter']?>";
        window.location.href="hpd.php?currentPage=" + page + "&" + value + "=" + clickedId + "&perPage=" + perPage + <?php if(isset($_GET['filter'])) echo '"&filter=" + myfilter +' ?> "&openModal=1";
    /*    $("#ownerItemsModal").on("hide.bs.modal", function(){
           refresh();
        });
    */
    }

    function helper(){
        <?php if(isset($_GET['openModal'])) { ?>
        $("#ownerItemsModal").modal("show");
        <?php } ?>
        <?php if(isset($_GET['edit'])) { ?>
        $("#editModal").modal("show");
        <?php } ?>
        <?php if (isset($_GET['editItem'])){ ?>
        $("#itemEditModal").modal("show");
        <?php } ?>
    }

   function refresh(){
        alert("hiding");
    }

</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
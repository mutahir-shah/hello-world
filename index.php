<?php
//load the database configuration file
include 'dbConfig.php';

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Members data has been inserted successfully.';
            break;
        case 'err':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusMsgClass = '';
            $statusMsg = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Team Leads and their members.</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style type="text/css">
    .panel-heading a{float: right;}
    #importFrm{margin-bottom: 20px;display: none;}
    #importFrm input[type=file] {display: inline;}
  </style>
</head>
<body>

<div class="container">
    <h2>Team Leads and their members.</h2>
    <?php if(!empty($statusMsg)){
        echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
    } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Members list
            <a href="javascript:void(0);" onclick="$('#importFrm').slideToggle();">Import Members</a>
        </div>
        <div class="panel-body">
            <form action="importData.php" method="post" enctype="multipart/form-data" id="importFrm">
                <input type="file" name="file" />
                <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
            </form>
            <?php 

 
function categoryParentChildTree($parent = 0, $spacing = '', $category_tree_array = '') {
    global $db;
    $parent = $db->real_escape_string($parent);
    if (!is_array($category_tree_array))
        $category_tree_array = array();
 
    $sqlCategory = "SELECT * FROM members WHERE manager = $parent ORDER BY id ASC";
    $resCategory=$db->query($sqlCategory);
  
    if ($resCategory->num_rows > 0) {
        while($rowCategories = $resCategory->fetch_assoc()) {

            $category_tree_array[] = array("id" => $rowCategories['id'], "name" => $spacing . $rowCategories['name'],'email'=>$rowCategories['email'], "phone" =>  $rowCategories['phone'], "created" => $rowCategories['created'], "status" =>$rowCategories['status']);

            $category_tree_array = categoryParentChildTree($rowCategories['id'], '&nbsp;&nbsp;&nbsp;&nbsp;'.$spacing . '-&rArr;&nbsp;', $category_tree_array);
        }
    }
    return $category_tree_array;
}


 

            ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Created</th>
                      <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

    
    $categoryList = categoryParentChildTree(); 
    foreach($categoryList as $key => $value){
        echo '<tr><td>'.$value['name'].'</td><td>'.$value['email'].'</td><td>'.$value['phone'].'</td><td>'.date("m/d/Y",strtotime($value['created'])).'</td><td>'.$value['status'].'</td></tr>';
    }
                   ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
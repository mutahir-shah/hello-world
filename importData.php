<?php
//load the database configuration file
include 'dbConfig.php';

if(isset($_POST['importSubmit'])){
    
    //validate whether uploaded file is a csv file
    $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            //skip first line
            fgetcsv($csvFile);
            $records = 1;
            $last_inserted_id = 0;
            //parse data from csv file line by line
            $db->query("TRUNCATE TABLE `members`");
            while(($line = fgetcsv($csvFile)) !== FALSE){
                //check whether member already exists in database with same email
                if($records == 1) { 
                    //insert member data into database
                    $db->query("INSERT INTO members (name, email, phone, created, modified, status) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".date("Y-m-d",strtotime($line[3]))."','".date("Y-m-d",strtotime($line[3]))."','".$line[4]."')");
                    $last_inserted_id = mysqli_insert_id($db);
                 

            }

            else { 
                    if($last_inserted_id!=0):
                    //insert member data into database
                    $db->query("INSERT INTO members (name, email, phone, created, modified, status,manager) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".date("Y-m-d",strtotime($line[3]))."','".date("Y-m-d",strtotime($line[3]))."','".$line[4]."','".$last_inserted_id."')");
                else:
                     //insert member data into database
                    $db->query("INSERT INTO members (name, email, phone, created, modified, status) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".date("Y-m-d",strtotime($line[3]))."','".date("Y-m-d",strtotime($line[3]))."','".$line[4]."')");
                    endif;
                 
                if($records == 6) $records=0;


            }// end else.
$records++;
            }
            
            //close opened csv file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

//redirect to the listing page
header("Location: index.php".$qstring);
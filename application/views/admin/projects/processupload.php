<?php
print_r($_FILES["codefile"]);
if (isset($_FILES["codefile"]) && $_FILES["codefile"]["error"] == UPLOAD_ERR_OK) {
    ############ Edit settings ##############
    //$UploadDirectory = 'C:/wamp/www/ajax-file-upload-64295/uploads/'; //specify upload directory ends with / (slash)
    $UploadDirectory = Yii::app()->getBaseUrl(true) . '/upload/images'; //specify upload directory ends with / (slash)
    ##########################################

    /*
      Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini".
      Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit
      and set them adequately, also check "post_max_size".
     */

    //check if this is an ajax request
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        die();
    }


    //Is file size is less than allowed size.
    if ($_FILES["codefile"]["size"] > 5242880) {
        die("File size is too big!");
    }

    //allowed file type Server side check
    switch (strtolower($_FILES['codefile']['type'])) {
        //allowed file types
        case 'application/vnd.ms-excel':

            break;
        default:
            die('Only CSV file allowed'); //output error
    }

    $File_Name = strtolower($_FILES['codefile']['name']);
    $File_Ext = substr($File_Name, strrpos($File_Name, '.')); //get file extention
//    $Random_Number = rand(0, 9999999999); //Random number to be added to name.
//    $NewFileName = $Random_Number . $File_Ext; //new file name
    $randomN = rand();
    $target = $UploadDirectory . $randomN . "translate.csv";
    $ok = 1;
    if (move_uploaded_file($_FILES['codefile']['tmp_name'], $target)) {

        die('Success! File Uploaded.');
    } else {
        $ok = 0;
        die('error uploading File!');
    }
    if ($ok == 1) {
        $query = 'CREATE TABLE IF NOT EXISTS {{temptranslate}} (ID INTEGER, ID2 INTEGER,
            PRIMARY  KEY (ID),INDEX (ID, ID2)) ENGINE=MyISAM';
        Yii::app()->db->createCommand($query)->query();
        //mysql_query($query) or die(mysql_error());
        
        $fp = fopen($target, 'r') or die("error1");
        $rownum = 1;
        while ($csv_line = fgetcsv($fp, 1024)) {
            if (is_numeric($csv_line[0])) {
                $query = 'INSERT IGNORE  INTO  temptranslate (ID, ID2) values (' . $csv_line[0] . ',' . $rownum . ' )';
                Yii::app()->db->createCommand($query)->query();
                //mysql_query($query) or die(mysql_error());
                $rownum++;
            } else {
                $ok = 0;
                ?>
                <img src="images/whitespace.png" onload="alert('Row <?php echo $rownum; ?> - 	<?php echo $csv_line[0]; ?> is not valid ID');" />
                <?php
                break;
            }
        }
        fclose($fp) or die("can't close file");
        /*
         * if(unlink($target)){ echo "Error in unlink file".$target; exit;} else { $ok=0;}
         */
        if ($ok == 1) {
            
        } else {
            $query = 'DROP TABLE temptranslate';
            Yii::app()->db->createCommand($query)->query();
            //$result = mysql_query($query) or die(mysql_error());
        }
    }
} else {
    die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
}
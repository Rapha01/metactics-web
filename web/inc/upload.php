<?php

function supportUpload($filesData)
{
	$target_dir = "uploads/support/";
	$target_file = $target_dir . basename($filesData["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	// Check if file already exists
	if (file_exists($target_file)) {
	    $uploadError = "Sorry, file is already uploaded";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileUpload"]["size"] > 1000000) {
	    $uploadError = "Sorry, your file is too large";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" && $imageFileType !="StormReplay") {
	    $uploadError = "Sorry, only JPG, JPEG, PNG & GIF files and Replays are allowed";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    //$uploadError = "Sorry, your file was not uploaded";
	    
	// if everything is ok, try to upload file
	} 
	else 
	{
	    if (move_uploaded_file($filesData["tmp_name"], $target_file)) {
	        //echo "The file ". basename( $filesData["name"]). " has been uploaded";
	    } 
	    else 
	    {
	        $uploadError = "Sorry, there was an error uploading your file";
	    }
	}
	
	if($uploadOk == 1){return 1;}else{return $uploadError;}
}


function avatarUpload($filesData)
{
	$target_dir = "uploads/avatars/";
	$target_file = $target_dir . basename($filesData["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	// Check if file already exists
	if (file_exists($target_file)) {
	    $uploadError = "Sorry, file is already uploaded";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileUpload"]["size"] > 5000000) {
	    $uploadError = "Sorry, your file is too large";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif") {
	    $uploadError = "Sorry, only JPG, JPEG, PNG & GIF files are allowed";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    //$uploadError = "Sorry, your file was not uploaded";
	    
	// if everything is ok, try to upload file
	} 
	else 
	{
	    if (move_uploaded_file($filesData["tmp_name"], $target_file)) {
	        //echo "The file ". basename( $filesData["name"]). " has been uploaded";
	    } 
	    else 
	    {
	        $uploadError = "Sorry, there was an error uploading your file";
	    }
	}
	
	if($uploadOk == 1){return 1;}else{return $uploadError;}
}

?>
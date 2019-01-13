<?php 
// including database connection
require_once("config.php");
 
// assigning post.html to $strUrl varaiable
$strUrl = 'post.html';
$strError = '';
 
// checks if the btn is valid or not
if(isset($_POST["btn"])) {
 
    // assigning the $_POST values to varaible
    $name = trim($mySqli->real_escape_string($_POST["txtName"]));
    $email = trim($mySqli->real_escape_string($_POST["txtEmail"]));
    $url = (isset($_POST["txtWebsite"])) ? trim($mySqli->real_escape_string($_POST["txtWebsite"])) : '';
    $comment = trim($mySqli->real_escape_string($_POST["txtComment"]));
    $ip = ip2long($_SERVER["REMOTE_ADDR"]);
    $datetime = strtotime("now");
     
    // if the validate false, it will concatinate the error message
    if($name=='') { $strError = '<br />Please enter the Name';}
    if(filter_var($email, FILTER_VALIDATE_EMAIL)==false) { $strError .= '<br />Please enter the valid Email';}
    if(($url!='' && filter_var($url, FILTER_VALIDATE_URL)==false)) { $strError .= '<br />Please enter the valid Url';}
    if($comment=='') { $strError .= '<br />Please enter the Comment';}
     
    // display the error messsage
    if($strError!='') { 
        echo $strError.'<br /><a href="javascript:history.back();">Back</a>';die;
    }
     
    // if there is no error message,  inserts into database
    if($strError=='') { 
        $strInsert = "insert into guestbook (Name,Email,Url,Comment,IP_Address,Date_Time) values ('".$name."','".$email."','".$url."','".$comment."',$ip,$datetime)";
        $mySqli->query($strInsert);
         
        // sending mail to admin for entry
        $strContent = "name : $name,email : $email,url : $url,comment : $comment, ip : $ip, datetime : $datetime";
        mail("admin@yourdomain.com","New entry",$strContent,"From:".$email);
    }
     
    $strUrl = 'post.html?a=y';
} 
// finally redirecting to the page which mentioned in the $strUrl varaible
header("Location: $strUrl");
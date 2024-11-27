<?php
include("db_connect.php");

if ($_SERVER['REQUEST_METHOD']=='POST'){
   

    $name=$_POST['name'];
    $email=$_POST['email'];
    $dob=$_POST['date'];
    $phone=$_POST['phone'];

 
    $table= 'students';
    $values=array(array(
        'name',$name,'STR'
    ),
    array('email',$email,'STR'),
    array('dob',$dob,'DATE'),
    array('phone',$phone,'STR'));

    $id=insertrow($table,$values);
    echo 'Entry inserted';


}
?>
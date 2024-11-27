<?php
include("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $student_id = $_POST['student_id'];
    $physics = $_POST['physics'];
    $chemistry = $_POST['chemistry'];
    $maths = $_POST['maths'];

    $total = $physics + $chemistry + $maths;

    $table = 'marks';
    $values = array(
        array('student_id', $student_id, 'INT'),
        array('physics', $physics, 'INT'),
        array('chemistry', $chemistry, 'INT'),
        array('maths', $maths, 'INT'),
        array('total', $total, 'INT')
    );

    $id = insertrow($table, $values);
    if ($id) {
        echo "Marks entry successful! Entry ID: " . $id;
    } else {
        echo "Error inserting marks.";
    }
}
?>

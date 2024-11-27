<?php
//change username, password and db_name 
$user='root';
$pass='password';
$pdoconnection = new PDO('mysql:localhost', $user, $pass);
$sth = $pdoconnection->prepare('use db_name');
$sth->execute();

date_default_timezone_set('Asia/Kolkata');



try {
    $pdo = new PDO("mysql:host=localhost;dbname=school", "root", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS["pdoconnection"] = $pdo;
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// function insertrow($table, $value) {
//     $pdo = $GLOBALS["pdoconnection"];
//     $fields = ''; // Initialize fields
//     $values = ''; // Initialize values

//     foreach ($value as $index => $item) {
//         if ($index == 0) { // First item
//             $fields = $item[0];
//             $values = ':' . $item[0];
//         } else { // Subsequent items
//             $fields .= ',' . $item[0];
//             $values .= ',:' . $item[0];
//         }
//     }

//     $sql = "INSERT INTO $table ($fields) VALUES ($values)";
//     $doquery = $pdo->prepare($sql);

//     foreach ($value as $item) {
//         if ($item[2] == 'INT') {
//             $curitem = filter_var($item[1], FILTER_VALIDATE_INT);
//             $doquery->bindValue(':' . $item[0], $curitem, PDO::PARAM_INT);
//         } elseif ($item[2] == 'STR' || $item[2] == 'HTML') {
//             $curitem = filter_var($item[1], FILTER_SANITIZE_STRING);
//             $curitem = strip_tags($curitem);
//             $doquery->bindValue(':' . $item[0], $curitem, PDO::PARAM_STR);
//         }elseif ($type === 'DATE') {
//             // Ensure the date format matches the database format (YYYY-MM-DD)
//             $stmt->bindValue(':' . $field, date('Y-m-d', strtotime($data)), PDO::PARAM_STR);
//         }
        
//     }

//     $doquery->execute();
//     $newId = $pdo->lastInsertId();
//     return $newId;
// }

function insertrow($table, $values) {
    $pdo = $GLOBALS["pdoconnection"];

    // Dynamically generate field names and placeholders
    $fields = [];
    $placeholders = [];
    foreach ($values as $value) {
        $fields[] = $value[0]; // Field name
        $placeholders[] = ':' . $value[0]; // Placeholder
    }

    // Generate the SQL query
    $fields_str = implode(',', $fields);
    $placeholders_str = implode(',', $placeholders);
    $sql = "INSERT INTO $table ($fields_str) VALUES ($placeholders_str)";
    $stmt = $pdo->prepare($sql);

    // Bind values to placeholders
    foreach ($values as $value) {
        $field = $value[0];
        $data = $value[1];
        $type = $value[2];

        if ($type === 'INT') {
            $stmt->bindValue(':' . $field, filter_var($data, FILTER_VALIDATE_INT), PDO::PARAM_INT);
        } elseif ($type === 'STR' || $type === 'HTML') {
            $stmt->bindValue(':' . $field, filter_var($data, FILTER_SANITIZE_STRING), PDO::PARAM_STR);
        } elseif ($type === 'DATE') {
            // Ensure the date format matches the database format (YYYY-MM-DD)
            $stmt->bindValue(':' . $field, date('Y-m-d', strtotime($data)), PDO::PARAM_STR);


        } else {
            throw new Exception("Unknown data type: $type");
        }
    }

    // Execute the query
    $stmt->execute();

    // Return the last inserted ID
    return $pdo->lastInsertId();
}


function allrows($table,$where,$order)
{
if($where=="1")
$wherestat="1";
else
{
foreach ($where as $items) {
if(!$wherestat)
$wherestat=$items[0]." = :".$items[0];
else
$wherestat=$wherestat." AND ".$items[0]." = :".$items[0];
}
}

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$wherestat.' ORDER BY '.$order);

if($where!="1")
foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}

$doquery->execute();
$row = $doquery->fetchAll();
return $row;
};




function rowslimit($table,$where,$order,$asc,$lim)
{
if($where=="1")
$wherestat="1";
else
{
foreach ($where as $items) {
$signwhere=$items[3];
if(!$items[3])
$signwhere="=";	

if(!$wherestat)
$wherestat=$items[0]." ".$signwhere." :".$items[0];
else
$wherestat=$wherestat." AND ".$items[0]." ".$signwhere." :".$items[0];
}
}

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$wherestat.' ORDER BY '.$order.' '.$asc.' LIMIT '.$lim);

if($where!="1")
foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}

$doquery->execute();
$row = $doquery->fetchAll();
return $row;
};



function alldistrows($table,$where,$dist,$order)
{
if($where=="1")
$wherestat="1";
else
{
foreach ($where as $items) {
if(!$wherestat)
$wherestat=$items[0]." = :".$items[0];
else
$wherestat=$wherestat." AND ".$items[0]." = :".$items[0];
}
}

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$wherestat.' GROUP BY '.$dist.' ORDER BY '.$order);

if($where!="1")
foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}

$doquery->execute();
$row = $doquery->fetchAll();
return $row;
};









function allrowswhere($table,$where,$values,$order)
{

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$where.' ORDER BY '.$order);

if($values)
{
foreach ($values as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}
}

$doquery->execute();
$row = $doquery->fetchAll();
return $row;
};







function getrow($table,$where)
{
if($where=="1")
$wherestat="1";
else
{
  
foreach ($where as $items) {
if(!$wherestat)
$wherestat=$items[0]." = :".$items[0];
else
$wherestat=$wherestat." AND ".$items[0]." = :".$items[0];
}
}

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$wherestat);

foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}

$doquery->execute();
$row = $doquery->fetch();
return $row;
};




function getfield($table,$infield,$value,$type,$getfield)
{
$wherestat=$infield." = :".$infield;
$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$wherestat);
if($type=='INT')
{
$curitem=filter_var($value, FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$infield, $curitem , PDO::PARAM_INT); 
}
if($type=='STR')
{
$curitem=filter_var($value, FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$infield, $curitem , PDO::PARAM_STR); 
}


$doquery->execute();
$row = $doquery->fetch();
return $row[$getfield];
};










function rownum($table,$where)
{
if($where=="1")
$wherestat="1";
else
{
foreach ($where as $items) {
if(!$wherestat)
$wherestat=$items[0]." = :".$items[0];
else
$wherestat=$wherestat." AND ".$items[0]." = :".$items[0];
}
}

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare('select * from '.$table.' where '.$wherestat);

foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}

$doquery->execute();
$total = $doquery->rowCount();
return $total;
};




function do_query($statement,$where)
{

$pdo=$GLOBALS["pdoconnection"];
$doquery = $pdo->prepare($statement);


foreach ($where as $items) {
	if($items[2]=='INT')
	{
	$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
	$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
	}
	if($items[2]=='STR')
	{
	$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
	$curitem=strip_tags($curitem);
	$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
	}

}

$doquery->execute();
$row = $doquery->fetchAll();
return $row;
};





function updaterow($table,$value,$where)
{
$pdo=$GLOBALS["pdoconnection"];

foreach ($value as $items) {
if(!$wherestat)
$wherestat=$items[0]." = :".$items[0];
else
$wherestat=$wherestat.", ".$items[0]." = :".$items[0];
}

foreach ($where as $items) {
if(!$wheresmt)
$wheresmt=$items[0]." = :".$items[0];
else
$wheresmt=$wheresmt." AND ".$items[0]." = :".$items[0];
}


$sql = "UPDATE ".$table." SET ".$wherestat." WHERE ".$wheresmt;
$doquery = $pdo->prepare($sql);
foreach ($value as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}


if($items[2]=='HTML')
{
$curitem=$items[1];
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}



}


foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
	
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}





}

$doquery->execute();
};










function deleterow($table,$where)
{
$pdo=$GLOBALS["pdoconnection"];

foreach ($where as $items) {
if(!$wheresmt)
$wheresmt=$items[0]." = :".$items[0];
else
$wheresmt=$wheresmt." AND ".$items[0]." = :".$items[0];
}


$sql = "DELETE FROM ".$table." WHERE ".$wheresmt;
$doquery = $pdo->prepare($sql);

foreach ($where as $items) {
if($items[2]=='INT')
{
$curitem=filter_var($items[1], FILTER_VALIDATE_INT);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_INT); 
}

if($items[2]=='STR')
{
$curitem=filter_var($items[1], FILTER_SANITIZE_STRING);
$curitem=strip_tags($curitem);
$doquery->bindValue(':'.$items[0], $curitem , PDO::PARAM_STR); 
}

}

$doquery->execute();
};






?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>test 1
    
    </title>
</head>
<body>
<?php
require_once "load.php";
// print "Hi ! my Name Is ".$obj -> computer_user("Eeshan")." And I Am ".$obj ->user_age(2004) . " Years Old. ";
print $obj->user_age("Eeshan", 2004);
print "<br>";
print $obj->hash_pass('123');
?>
    
</body>
</html>
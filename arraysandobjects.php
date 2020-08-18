<?php

// Two types of arrays: INDEXED, ASSOCIATIVE

// Creating an INDEXED array
$myArr = array("Milk", "Bread", "Cheese", 42, true);

var_dump($myArr);

echo "<br>The first thing on the list is $myArr[0]";

echo "<br>But here's the full list:<ul>";
foreach($myArr as $v) {
  echo "<li>$v</li>";
}
echo "</ul>";

$myAssoc = array("name" => "Edison", "age" => 3);
var_dump($myAssoc);

echo "<br>Get associative 'name': " . $myAssoc["name"]; 

echo "<br>" . json_encode($myAssoc) . "<br>";

$decoded = json_decode('{"power":"Fluffiness", "strength": 10000}', true);
var_dump($decoded);

echo "<br>Decoded power: " . $decoded->power; 

?>
<hr>
<?php

echo json_encode($myArr);
echo "<hr>";

$subArray = array("val1", "val2", $myArr, 12, $decoded);
echo json_encode($subArray);

?>
<?php

function passByValue($val)
{
  $val = $val + 100;
}

function passByReference(&$val)
{
  $val = $val + 100;
}

$v1 = false;
$v2 = 34;

passByValue($v1);
passByReference($v2);

var_dump($v1);
var_dump($v2);


function passArrayByValue($arr)
{
  array_push($arr, "Toaster Strudels");
}

function passArrayByReference(&$arr)
{
  array_push($arr, "Eggos");
}

$groceries = array("Milk", "Bread", "Cheese");

echo "<hr>";
var_dump($groceries);

passArrayByValue($groceries);
passArrayByReference($groceries);

echo "<hr>";
var_dump($groceries);


function passObjectByValue($obj)
{
  $obj->byValue = "New value added byValue";
}

function passObjectByReference(&$obj)
{
  $obj->byReference = "New value added byReference";
}

$myCar = new stdClass();
$myCar->make = "Hyundai";
$myCar->model = "Elantra";

passObjectByValue($myCar);
passObjectByReference($myCar);

// Functions can have default values too

function foo($arg = "Default Values")
{
  echo "<br>Foo bar " . $arg;
}

foo("CHicken soup");
foo();

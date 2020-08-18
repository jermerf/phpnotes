<?php

$someVar = "Kittens";

var_dump($someVar);

echo "<br>";
$anotherVar = "<br>I love $someVar so much. And there is newline here\n and then a br here<br> okay";

var_dump($anotherVar);

echo "<br>";
$singleQuoteVar = '<br>I love $someVar so much. And there is newline here\n and then a br here<br> okay';

var_dump($singleQuoteVar);

?>

<div>
  I'm in a div


  I am three lines lower
</div>
<hr>
<?php

// Heredoc
$multiLineString = <<<MULTILINE
<h2>Heredoc</h2>
<p>
  Some words that can be on multiple lines
  They can also use variables like - $someVar - 
  The just need to use the three angle braces followed
  by an identifier (like MULTILINE) for closing. Then,
  to end the heredoc string have a new line with JUST
  the identifier from the beginning.
</p>
MULTILINE;

echo $multiLineString;
echo $multiLineString;

$multilineNowdoc = <<<'SPECIALIDENTIFIER'
<p>
  Nowdocs are the same as heredocs except:
  <ul>
    <li>The identifier is wrapped in single quotes at the beginning</li>
    <li>Nowdocs are like single quote strings so they ignore - $someVar - </li>
    <li>No single quote at the end</li>
  </ul>
</p>
SPECIALIDENTIFIER;

echo $multilineNowdoc;
?>
<hr>
<pre>
<?php
// htmlentities
echo htmlentities($multilineNowdoc);
?>

<h2>String Comparisons</h2>
<?php

$str1 = "Kittens";
$str2 = "Kittens";
$str3 = "dogs";

$eq = ($str1 == $str2 ? "TRUE" : "FALSE");
echo "Comparing '$str1' == '$str2' gives $eq<br>";

$eq = ($str1 == $str3 ? "TRUE" : "FALSE");
echo "Comparing '$str1' == '$str3' gives $eq<br>";

$num = 0;
$eq = ($str1 == $num ? "TRUE" : "FALSE");
echo "Comparing '$str1' == '$num' gives $eq<br>";
?>
<h3>Don't compare strings with ==, use strcmp()</h3>
<p> strcmp() compares HOW two strings are different.</p>
<ul>
  <li>< 0 If first is less than second</li>
  <li>> 0 If first is more than second</li>
  <li>= 0 If first and second are equivalent</li>
  <li>strcmp() is case sensitive</li>
</ul>
<?php

$eq = strcmp($str1, $str2);
echo "Comparing strcmp('$str1', '$str2') gives $eq<br>";

$eq = strcmp($str1, $str3);
echo "Comparing strcmp('$str1', '$str3') gives $eq<br>";

$num = 0;

$eq = strcmp($str1, $num);
echo "Comparing strcmp('$str1', '$num') gives $eq<br>";

$eq = strcmp($str3, $num);
echo "Comparing strcmp('$str3', '$num') gives $eq<br>";
?>

<h3>Comparing with < and > compares alphabetically</h3>
<?php

echo "cats &lt; dogs &rarr; " . ("cats" < "dogs" ? 'true' : 'false'); //true
echo "<br>cats &gt; dogs &rarr; " . ("cats" > "dogs" ? 'true' : 'false'); //false
echo "<br>Cats &lt; cats &rarr; " . ("Cats" < "cats" ? 'true' : 'false'); //true
echo "<br>Cats &gt; cats &rarr; " . ("Cats" > "cats" ? 'true' : 'false'); //false
echo "<br><br>cats &gt; Dogs &rarr; " . ("cats" > "Dogs" ? 'true' : 'false'); //true

?>
<h3>Explosive Code</h3>
<?php

$str = "Once upon a midnight dreary as I pondered etc.";

$words = explode(" ", $str);
var_dump($words);
$strRebuilt = implode(",", $words);

var_dump($strRebuilt);

?>
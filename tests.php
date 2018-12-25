<?php
/**
 * Created by PhpStorm.
 * User: shady
 * Date: 12/25/18
 * Time: 9:14 AM
 */


include("JsonToHtml.php");


$jthml = new JsonToHtml();
$jthml->readFile("data.json");
$jthml->writeToFile("demo.php");
echo $jthml->getOutput();
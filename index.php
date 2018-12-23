<?php
/**
 * Created by PhpStorm.
 * User: shady
 * Date: 12/21/18
 * Time: 11:37 AM
 */

require 'vendor/autoload.php';


include("JsonToHtml.php");


$jthml = new JsonToHtml();
$jthml->readFile("data.json");
echo $jthml->getTestOutput();
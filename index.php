<?php
/**
 * Created by PhpStorm.
 * User: shady
 * Date: 12/21/18
 * Time: 11:37 AM
 */

include("JsonToHtml.php");

use wfranklin\JsonToHtml;

$jthml = new JsonToHtml();
$jthml->readFile("data.json");
echo $jthml->getTestOutput();
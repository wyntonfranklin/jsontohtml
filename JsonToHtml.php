<?php
/**
 * Created by PhpStorm.
 * User: shady
 * Date: 12/21/18
 * Time: 11:36 AM
 */

namespace wfranklin;


class JsonToHtml
{
    private $_rawJsonData;
    private $_jsonAsArray;
    private $_output;
    private $closeElements = array();
    /**
     * JsonToHtml constructor.
     */
    public function __construct()
    {


    }

    public function readFile($file){
        $file = fopen(__DIR__.'/' . $file,'r');
        while (!feof($file)){
            $line = fgets($file);
            $this->parseLine($line);
        }
        //var_dump($this->closeElements);
    }

    private function parseLine($line)
    {
        $closeElements = array();
        $semPosition = strpos($line, ":");
        if($semPosition !== false){
            $before = $this->formatData(substr($line, 0, $semPosition));
            $after =  $this->formatData(substr($line, $semPosition+1));
            echo "before- " . trim($before). " : ";
            echo "after- " . trim($after) ."\n";
            $this->createHtmlElement($before, $after);
            if($after === "{"){
               $this->closeElements[] = $before;
            }

        }else{
            $bracket = trim($line);
            if($bracket !== "{"){
                $bracket = $this->formatData($line);
                $popped = array_pop($this->closeElements);
                echo "braket : " . $bracket . "\n";
                echo "closing elemnt: " . $popped . "\n";
                $this->closeElement($popped);
            }
        }


    }

    private function formatData($text)
    {
        return str_replace('"', "", trim($text));
    }

    private function createHtmlElement($element, $content)
    {
        if($content==="{"){
            if($element === "html"){
                $this->addOutput("<html>");
            }else if($element === "h1"){
                $this->addOutput("<h1>");
            }else if( $element === "p"){
                $this->addOutput("<p>");
            }
        }else{
            if($element === "html"){
                $this->addOutput("<html>");
            }else if($element === "h1"){
                $this->addOutput("<h1>". $content. "</h1>");
            }else if( $element === "p"){
                $this->addOutput("<p>" . $content . "</p>");
            }
        }
    }

    private function closeElement($type){
        if($type === "html"){
            $this->addOutput("</html>");
        }else if($type === "h1"){
            $this->addOutput("</h1>");
        }else if( $type === "p"){
            $this->addOutput("</p>");
        }
    }

    public function getTestOutput()
    {
        return $this->getOutput();
    }

    public function setJsonData($data)
    {
        $this->_rawJsonData = $data;
    }

    public function getRawJsonData()
    {
        return $this->_rawJsonData;
    }

    private function generateHtml()
    {
        $subject = $this->getRawJsonData();
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $subject) as $line){
            // do stuff with $line
            $this->addOutput($line). '/n';
        }
       return $this->getOutput();

    }

    private function iterateJsonArray($data)
    {
        foreach ($data as $key=>$value){
            if(is_array($value)) {
                $this->iterateJsonArray($value);
            }else {
                $this->addOutput("$key => $value\n");
            }
        }
        return $this->getOutput();
    }


    public function getHtmlData()
    {
       $output = $this->generateHtml();
       return $output;
    }

    private function getOutput()
    {
        return $this->_output;
    }

    private function setOutput( $data )
    {
        $this->_output = $data;
    }

    private function addOutput($content)
    {
        $this->_output .= $content;
        return $this->_output;
    }

    private function convertJsonToArray($json)
    {
        var_dump($json);
        $this->_jsonAsArray = json_decode($json, TRUE);
        var_dump($this->_jsonAsArray);
        return $this->_jsonAsArray;
    }

    private function getJsonAsArray()
    {
        return $this->_jsonAsArray;
    }


}
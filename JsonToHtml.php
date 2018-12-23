<?php
/**
 * Created by PhpStorm.
 * User: shady
 * Date: 12/21/18
 * Time: 11:36 AM
 */

include("HtmlElement.php");

class JsonToHtml
{

    const HTML_TAG="html";
    const P_TAG ="p";
    const H1_TAG="h1";

    private $_rawJsonData;
    private $_jsonAsArray;
    private $_output;
    private $closeElements = array();
    private $_lineElements = array();
    private $_childrenElements = array();
    private $root;

    /**
     * JsonToHtml constructor.
     */
    public function __construct()
    {
        $root = new Tree\Node\Node('document');

    }

    public function readFile($file){
        $file = fopen(__DIR__.'/' . $file,'r');
        while (!feof($file)){
            $line = fgets($file);
            $this->parseLine($line);
        }
        $this->createHtmlOutput();
        //var_dump($this->closeElements);
    }

    private function createHtmlOutput()
    {
        foreach($this->_lineElements as $htmlElement){
            echo $htmlElement->createHtml() . "\n";
        }
        echo '--------child  elements-------'."\n";
        foreach($this->_childrenElements as $htmlElement){
            echo $htmlElement->createHtml() . "\n";
        }
        echo '--------full elements-------'."\n";
        $length = count($this->_childrenElements)-1;
        $children = $this->_childrenElements;
        $o= "";
        $parent = "";
        for($i=0; $i<=$length; $i++){
            if($i!==0){
                $o .= $children[$i]->createHtml();
            }
        }
        $parent = $children[0]->addContent($o);
        $o = $parent->createHtml();
        $this->writeToFile($o);
        echo $o;
    }

    private function writeToFile($output)
    {
        file_put_contents("demo.html", $output);
    }

    private function parseLine($line)
    {
        $closeElements = array();
        $semPosition = strpos($line, ":");
        if($semPosition !== false){
            $before = $this->formatData(substr($line, 0, $semPosition));
            $after =  $this->formatData(substr($line, $semPosition+1));
           // echo "before- " . trim($before). " : ";
           // echo "after- " . trim($after) ."\n";
            $hE = $this->createHtmlElement($before, $after);
            $this->_lineElements[] = $hE;
            if($after === "{"){
               $this->closeElements[] = $before;
            }
        }else{
            $bracket = trim($line);
            if($bracket !== "{"){
                $bracket = $this->formatData($line);
                $popped = array_pop($this->closeElements);
               // echo "braket : " . $bracket . "\n";
               // echo "closing elemnt: " . $popped . "\n";
                //$this->closeElement($popped);
            }
        }
    }

    private function getLastInElement()
    {
        $popped = array_pop($this->_lineElements);
        return $popped;
    }

    private function addLineElement(HtmlElement $el)
    {
        array_push($this->_lineElements, $el);
    }

    private function formatData($text)
    {
        return str_replace('"', "", trim($text));
    }

    private function createHtmlElement($element, $content)
    {
        $htmlElement = new HtmlElement();
        if($this->isHtmlTag($element)){
            $htmlElement->create($element);
            if($content==="{"){
                $this->_childrenElements[] = $htmlElement;
                $htmlElement->setHasChildren(true);
            }else{
                $htmlElement->setContent($content);
                $child = array_pop($this->_childrenElements);
                if($child !== null ){
                    if($child->getHasChildren()){
                        $child->addContent($htmlElement->createHtml());
                        array_push($this->_childrenElements, $child);
                    }
                }
            }
        }else{
            $htmlElement = $this->getLastInElement();
            if($htmlElement !== null){
                $htmlElement->addAttributes($element, $content);
               // $this->addLineElement($htmlElement);
            }
        }
        return $htmlElement;
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

    private function isHtmlTag($el)
    {
        $tags = array("html","h1","p","h2","div");
        if(in_array($el, $tags)){
            return true;
        }
        return false;
    }


}
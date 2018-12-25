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
    const OPEN_BRACKET = "{";
    const SEMI_COLON = ":";

    private $_output;
    private $closeElements = array();
    private $_lineElements = array();
    private $_childrenElements = array();

    /**
     * JsonToHtml constructor.
     */
    public function __construct( $file="" )
    {
        if(!empty($file)){
            $this->readFile($file);
        }
    }

    public function readFile($file)
    {
        $file = @fopen(__DIR__.'/' . $file,'r');
        if($file){
            while (!feof($file)){
                $line = fgets($file);
                $this->parseLine($line);
            }
            $this->createHtmlOutput();
        }
       return $this->getOutput();
    }


    private function createHtmlOutput()
    {
        return $this->getElementsTreeOutput();
    }

    private function getElementsTreeOutput()
    {
        $this->recursiveElementTraverse($this->_childrenElements[0]);
        $o = $this->_childrenElements[0]->getHtml();
        $this->setOutput($o);
        return $o;
    }

    private function recursiveElementTraverse($element)
    {
        if($element->getChildrenCount() >0 ){
            foreach ($element->getChildren() as $child) {
                $this->recursiveElementTraverse($child);
            }
        }else{
           $this->recursiveAddToParent($element);
        }
    }

    private function recursiveAddToParent($element){
        if($element!== null){
            $parent = $element->getParent();
            if($parent!== null){
                //echo "Parents: " .$parent->getHtml() . "\n";
                $parent->addContent($element->getHtml());
               // echo "Childs: " .$element->getHtml() . "\n";
                $parent->removeChild();
                if($parent->getChildrenCount()==0){
                    $this->recursiveAddToParent($parent);
                }
            }else{

            }
        }
    }

    public function writeToFile($filename="")
    {
        try{
            file_put_contents($filename, $this->getOutput());
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

    private function parseLine($line)
    {
        $closeElements = array();
        $semPosition = strpos($line, self::SEMI_COLON );
        if($semPosition !== false){
            $before = $this->formatData(substr($line, 0, $semPosition));
            $after =  $this->formatData(substr($line, $semPosition+1));
            $hE = $this->createHtmlElement($before, $after);
            $this->_lineElements[] = $hE;
            if($after === self::OPEN_BRACKET){
               $this->closeElements[] = $before;
            }
        }else{
            $bracket = trim($line);
            if($bracket !== self::OPEN_BRACKET ){
                $sizeOfChildren = count($this->_childrenElements);
                if($sizeOfChildren!=1){
                    $child = array_pop($this->_childrenElements);
                    $sizeOfChildren = count($this->_childrenElements);
                    $parent = $this->_childrenElements[$sizeOfChildren-1];
                    if($parent!==null){
                        $parent->addChild($child);
                    }
                }
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
        $data = str_replace('"', "", trim($text));
        $lastElement = substr($data, -1);
        if(strcmp($lastElement,",")==0){
            return substr($data, 0, -1);
        }else{
            return $data;
        }
    }

    private function createHtmlElement($element, $content)
    {
        $htmlElement = new HtmlElement();
        if($this->isHtmlTag($element)){
            $htmlElement->create($element);
            if( $content===self::OPEN_BRACKET ){
                $this->_childrenElements[] = $htmlElement;
            }else{
                $htmlElement->setContent($content);
                $child = array_pop($this->_childrenElements);
                if($child !== null ){
                    $child->addChild($htmlElement);;
                    array_push($this->_childrenElements, $child);
                }
            }
        }else{
            $htmlElement = $this->getLastInElement();
            if($htmlElement !== null){
                if($htmlElement->isVerifiedAttribue($element)){
                    $htmlElement->addAttributes($element, $content);
                }else{
                    $htmlElement->addGeneralAttributes($element, $content);
                }
            }
        }
        return $htmlElement;
    }


    public function getTestOutput()
    {
        return $this->getOutput();
    }


    public function getOutput()
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


    private function isHtmlTag($el)
    {
        $tags = ["html","h1","p","h2","div","a","span",
            "h3","h4","h5","h6","script","head","header","footer","title","style",
            "ul","li","ol","section","article","pre","body","nav","hr","img"
            ,"iframe","table","tr","td","th","form","input","label","br",
            "select","option","textarea","meta","link"];
        if(in_array($el, $tags)){
            return true;
        }
        return false;
    }



}
<?php
/**
 * Created by PhpStorm.
 * User: shady
 * Date: 12/23/18
 * Time: 3:15 AM
 */


class HtmlElement
{


    private $_openTag;
    private $_close_Tag;
    private $_tag;
    private $_content;
    private $_attributes;
    private $_style;
    private $_class;
    private $_id;
    private $_href;
    private $_hasChildren=false;
    private $children= array();
    private $parent;
    private $_generalAttributes=array();


    /**
     * HtmlElement constructor.
     */
    public function __construct($type="")
    {
        if(!empty($type)){
            $this->create($type);
        }
    }


    public function create( $type )
    {
        $this->setOpenAndCloseTags($type);
    }

    private function setOpenAndCloseTags( $type )
    {
        $this->setTag($type);
    }


    /**
     * @return mixed
     */
    public function getOpenTag()
    {
        return $this->_openTag;
    }

    /**
     * @param mixed $openTag
     */
    public function setOpenTag( $openTag )
    {
        $this->_openTag = $openTag;
    }

    /**
     * @return mixed
     */
    public function getCloseTag()
    {
        return $this->_close_Tag;
    }

    /**
     * @param mixed $close_Tag
     */
    public function setCloseTag( $close_Tag )
    {
        $this->_close_Tag = $close_Tag;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * @param mixed $content
     */
    public function setContent( $content )
    {
        $this->_content = $content;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        $attibuteMembers = ['class','id','style','href'];
        $o = '';
        foreach ($attibuteMembers as $member){
            $classMember = "_" . $member;
            if(!empty($this->$classMember)){
                $o .= $this->getAttibMemberRealName($member) . '="'.$this->$classMember.'" ';
            }
        }
        $this->setAttributes($o);
        return $this->_attributes;
    }

    private function getAttibMemberRealName($psudoName)
    {
        if($psudoName === 'inline'){
            return "style";
        }
        return $psudoName;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes( $attributes )
    {
        $this->_attributes = $attributes;
    }

    /**
     * @return bool
     */
    public function getHasChildren()
    {
        return $this->_hasChildren;
    }

    /**
     * @param bool $hasChildren
     */
    public function setHasChildren( $hasChildren )
    {
        $this->_hasChildren = $hasChildren;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->_tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag( $tag )
    {
        $this->_tag = $tag;
    }

    /**
     * @return mixed
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * @param mixed $style
     */
    public function setStyle($style)
    {
        $this->_style = $style;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->_class = $class;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->_href;
    }

    /**
     * @param mixed $href
     */
    public function setHref($href)
    {
        $this->_href = $href;
    }

    /**
     * @return mixed
     */
    public function getGeneralAttributes()
    {
        return $this->_generalAttributes;
    }

    /**
     * @param mixed $generalAttributes
     */
    public function setGeneralAttributes($attributes)
    {
        $this->_generalAttributes = $attributes;
    }


    public function addGeneralAttributes($param, $value)
    {
        $this->_generalAttributes[$param] = $value;
    }


    public function createGeneralAttributes()
    {
        $o = "";
        foreach ($this->getGeneralAttributes() as $name => $value ){
            $o .= $name . '="'.$value.'" ';
        }
        return $o;
    }



    public function addContent( $content )
    {
        $currentContent = $this->getContent();
        $this->setContent($currentContent . $content );
        return $this;
    }

    public function addAttributes($type, $attribs)
    {
        if($type == "text"){
            $this->addContent($attribs);
        }else if($type == "inline"){
           $this->setStyle($attribs);
        }else if($type == "class"){
            $this->setClass($attribs);
        }else if($type == "id"){
            $this->setId($attribs);
        }else if($type == "href"){
            $this->setHref($attribs);
        }
    }


    public function isVerifiedAttribue($attrib)
    {
        $verifiedMembers = ['class','id','inline','href','text'];
        if(in_array($attrib, $verifiedMembers)){
            return true;
        }
        return false;
    }


    private function addClasses( $options )
    {
        $classOptions = '';
        foreach( $options as $option=>$value){
            if( !empty( $value ) ){
                $classOptions .= $option.'='.'"'.$value.'"'.' ';
            }
        }
        return $classOptions;
    }

    public function getHtml()
    {
        $o = "<" . $this->getTag();
        if($this->isSpecialTag()){
            $o .= " " . $this->getAttributes() . $this->createGeneralAttributes() . "/>";
        }else{
            $o .= " " . $this->getAttributes() . $this->createGeneralAttributes() . ">";
            $o .= $this->getContent();
            $o .= "</" . $this->getTag() . ">";
        }
        return $o;
    }

    public function isSpecialTag()
    {
        $specialElements = ["hr","br"];
        if(in_array($this->getTag(), $specialElements)){
            return true;
        }
    }

    public function addChild($element)
    {
        $element->parent = $this;
        $this->children[] = $element;
    }

    public function getChildrenCount()
    {
        return count($this->children);
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function hasParent()
    {
        if($this->getParent() !== null ){
            return true;
        }
        return false;
    }

    public function removeChild()
    {
        array_pop($this->children );
    }

    public function removeChildren()
    {
        $this->children = array();
    }



}
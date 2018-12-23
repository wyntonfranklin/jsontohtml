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
    private $_hasChildren=false;


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
        $attibuteMembers = ['class','id','style'];
        $o = '';
        foreach ($attibuteMembers as $member){
            $classMember = "_" . $member;
            if(!empty($this->$classMember)){
                $o .= $member . '="'.$this->$classMember.'" ';
            }
        }
        $this->setAttributes($o);
        return $this->_attributes;
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
        }else if($type == "style"){
           $this->setStyle($attribs);
        }else if($type == "class"){
            $this->setClass($attribs);
        }else if($type == "id"){
            $this->setId($attribs);
        }
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

    public function createHtml()
    {
        $o = "<" . $this->getTag();
        $o .= " " . $this->getAttributes() . ">";
        $o .= $this->getContent();
        $o .= "</" . $this->getTag() . ">";
        return $o;
    }


}
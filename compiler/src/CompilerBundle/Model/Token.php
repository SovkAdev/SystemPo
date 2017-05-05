<?php

namespace CompilerBundle\Model;

class Token {

    protected $id;
    protected $value;
    protected $tag;

    /**
     * Constructor
     */
    public function __construct() {
        $this->id = NULL;
        $this->value = NULL;
        $this->tag = NULL;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getValue() {
        return $this->value;
    }

    function getTag() {
        return $this->tag;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setTag($tag) {
        switch ($tag) {
            case -2: $this->tag = 'Unexpected symbol - ' . $this->value;
                break;
            case -1: $this->tag = 'Unknown identifier - ' . $this->value;
                break;
            case 0: $this->tag = 'NUM';
                break;
            case 1: $this->tag = 'ID';
                break;
            case 2: $this->tag = 'IF';
                break;
            case 3: $this->tag = 'ELSE';
                break;
            case 4: $this->tag = 'WHILE';
                break;
            case 5: $this->tag = 'DO';
                break;
            case 6: $this->tag = 'LBRA';
                break;
            case 7: $this->tag = 'RBRA';
                break;
            case 8: $this->tag = 'LPAR';
                break;
            case 9: $this->tag = 'RPAR';
                break;
            case 10: $this->tag = 'PLUS';
                break;
            case 11: $this->tag = 'MINUS';
                break;
            case 12: $this->tag = 'LESS';
                break;
            case 13: $this->tag = 'EQUAL';
                break;
            case 14: $this->tag = 'SEMICOLON';
                break;
        }
    }

}

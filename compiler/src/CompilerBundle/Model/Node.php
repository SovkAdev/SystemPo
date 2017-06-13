<?php

namespace CompilerBundle\Model;

class Node {

    protected $kind;
    protected $value;
    protected $op1;
    protected $op2;
    protected $op3;

    public function __construct($kind, $value, $op1, $op2, $op3) {
        $this->kind = $kind;
        $this->value = $value;
        $this->op1 = $op1;
        $this->op2 = $op2;
        $this->op3 = $op3;
    }

    function getKind() {
        return $this->kind;
    }

    function getValue() {
        return $this->value;
    }

    function getOp1() {
        return $this->op1;
    }

    function getOp2() {
        return $this->op2;
    }

    function getOp3() {
        return $this->op3;
    }

    function setKind($kind) {
        $this->kind = $kind;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setOp1($op1) {
        $this->op1 = $op1;
    }

    function setOp2($op2) {
        $this->op2 = $op2;
    }

    function setOp3($op3) {
        $this->op3 = $op3;
    }

}

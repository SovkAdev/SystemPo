<?php

namespace CompilerBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Compiler {

    protected $program;
    protected $pc;

    public function __construct() {
        $this->program = new ArrayCollection();
        $this->pc = 0;
    }

    public function gen($command) {
        $this->program->add($command);
        $this->pc += 1;
    }

    public function compile($node) {
       
        if ($node->getKind() == 'VAR') {
            $this->gen('IFETCH');
            $this->gen($node->getValue());
        } elseif ($node->getKind() == 'CONST') {
            $this->gen('IPUSH');
            $this->gen($node->getValue());
        } elseif ($node->getKind() == 'ADD') {
            $this->compile($node->getOp1());
            $this->compile($node->getOp2());
            $this->gen('IADD');
        } elseif ($node->getKind() == 'SUB') {
            $this->compile($node->getOp1());
            $this->compile($node->getOp2());
            $this->gen('ISUB');
        } elseif ($node->getKind() == 'LT') {
            $this->compile($node->getOp1());
            $this->compile($node->getOp2());
            $this->gen('ILT');
        } elseif ($node->getKind() == 'SET') {
            $this->compile($node->getOp2());
            $this->gen('ISTORE');
            $this->gen($node->getOp1()->getValue());
        } elseif ($node->getKind() == 'IF1') {
            $this->compile($node->getOp1());
            $this->gen('JZ');
            $addr = $this->pc;
            $this->gen(0);
            $this->compile($node->getOp2());
            $this->program[$addr] = $this->pc;
        } elseif ($node->getKind() == 'IF2') {
            $this->compile($node->getOp1());
            $this->gen('JZ');
            $addr1 = $this->pc;
            $this->gen(0);
            $this->compile($node->getOp2());
            $this->gen('JMP');
            $addr2 = $this->pc;
            $this->gen(0);
            $this->program[$addr1] = $this->pc;
            $this->compile($node->getOp3());
            $this->program[$addr2] = $this->pc;
        } elseif ($node->getKind() == 'WHILE') {
            $addr1 = $this->pc;
            $this->compile($node->getOp1());
            $this->gen('JZ');
            $addr2 = $this->pc;
            $this->gen(0);
            $this->compile($node->getOp2());
            $this->gen('JMP');
            $this->gen($addr1);
            $this->program[$addr2] = $this->pc;
        } elseif ($node->getKind() == 'DO') {
            $addr = $this->pc;
            $this->compile($node->getOp1());
            $this->compile($node->getOp2());
            $this->gen('JNZ');
            $this->gen($addr);
        } elseif ($node->getKind() == 'SEQ') {
            $this->compile($node->getOp1());
            $this->compile($node->getOp2());
        } elseif ($node->getKind() == 'EXPR') {
            $this->compile($node->getOp1());
            $this->gen('IPOP');
        } elseif ($node->getKind() == 'PROGRAMMA') {
            $this->compile($node->getOp1());
            $this->gen('HALT');
        }
        return $this->program;
    }

}

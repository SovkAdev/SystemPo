<?php

namespace CompilerBundle\Model;

class VirtualMachine {

    protected $IFETCH = 0, $ISTORE = 1, $IPUSH = 2, $IPOP = 3, $IADD = 4,
            $ISUB = 5, $ILT = 6, $JZ = 7, $JNZ = 8, $JMP = 9, $HALT = 10;

    public function run($program) {
        
    }

}

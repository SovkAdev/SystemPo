<?php

namespace CompilerBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class VirtualMachine {

    protected $IFETCH = 'IFETCH', $ISTORE = 'ISTORE', $IPUSH = 'IPUSH',
            $IPOP = 'IPOP', $IADD = 'IADD', $ISUB = 'ISUB',
            $ILT = 'ILT', $JZ = 'JZ', $JNZ = 'JNZ',
            $JMP = 'JMP', $HALT = 'HALT';

    public function run($program) {
        $var = array_fill(0, 26, 0);
        $stack = [];
        $pc = 0;
        while (true) {
            $op = $program[$pc];
            if ($pc < $program->count() - 1) {
                $arg = $program[$pc + 1];
            }

            if ($op == $this->IFETCH) {
                $stack[] = $var[$arg];
                $pc+=2;
            } elseif ($op == $this->ISTORE) {
                $var[$arg] = array_pop($stack);
                $pc+=2;
            } elseif ($op == $this->IPUSH) {
                $stack[] = $arg;
                $pc+=2;
            } elseif ($op == $this->IPOP) {
                $stack[] = $arg;
                array_pop($stack);
                $pc+=1;
            } elseif ($op == $this->IADD) {
                dump($stack);
                $stack[0] += $stack[1];
                array_pop($stack);
                $pc+=1;
            } elseif ($op == $this->ISUB) {
                $stack[0] -= $stack[1];
                array_pop($stack);
                $pc+=1;
            } elseif ($op == $this->ILT) {
                if ($stack[0] < $stack[1]) {
                    $stack[0] = 1;
                } else {
                    $stack[0] = 0;
                }
                array_pop($stack);
                $pc+=1;
            } elseif ($op == $this->JZ) {
                if (array_pop($stack) == null) {
                    $pc = $arg;
                } else {
                    $pc +=2;
                }
            } elseif ($op == $this->JNZ) {
                if (array_pop($stack) != null) {
                    $pc = $arg;
                } else {
                    $pc +=2;
                }
            } elseif ($op == $this->JMP) {
                $pc = $arg;
            } elseif ($op == $this->HALT) {
                break;
            }
        }


        echo '<h5 style="color:white;padding: 10px 15px">Execution finished.<br><br>';
        for ($i = 0; $i < 26; $i++) {
            if ($var[$i] != 0) {
                echo chr($i + ord('a')) . ' = ' . $var[$i] . '<br>';
            }
        }
        echo '</h5>';
    }

}

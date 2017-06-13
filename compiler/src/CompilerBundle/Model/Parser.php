<?php

namespace CompilerBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use CompilerBundle\Model\Node;

class Parser {

    protected $tokens, $token, $errors;
    protected $VAR = "VAR", $CONST = "CONST", $ADD = "ADD", $SUB = "SUB", $LT = "LT",
            $SET = "SET", $IF1 = "IF1", $IF2 = "IF2", $WHILE = "WHILE", $DO = "DO",
            $EMPTY = "EMPTY", $SEQ = "SEQ", $EXPR = "EXPR", $PROG = "PROGRAMMA";

    public function __construct() {
        $this->tokens = new ArrayCollection();
        $this->errors = new ArrayCollection();
    }

    function setTokens($tokens) {
        $this->tokens = $tokens;
    }

    function nextToken() {
        $this->token = $this->tokens->next();
    }

    function parse() {
        $this->token = $this->tokens->first();
        $Node = new Node($this->PROG, null, $this->statement(), null, null);
        return $Node;
    }

    function statement() {
        if ($this->token->getTag() == 'IF') {
            $n = new Node($this->IF1, null, null, null, null);
            $this->nextToken();
            $n->setOp1($this->paren_expr());
            $n->setOp2($this->statement());
            if ($this->token->getTag() == 'ELSE') {
                $n->setKind($this->IF2);
                $this->nextToken();
                $n->setOp3($this->statement());
            }
        } elseif ($this->token->getTag() == 'WHILE') {
            $n = new Node($this->WHILE, null, null, null, null);
            $this->nextToken();
            $n->setOp1($this->paren_expr());
            $n->setOp2($this->statement());
        } elseif ($this->token->getTag() == 'DO') {
            $n = new Node($this->DO, null, null, null, null);
            $this->nextToken();
            $n->setOp1($this->statement());
            if ($this->token->getTag() != 'WHILE') {
                $this->errors->set($this->token->getId(), "'while' expected");
            }
            $this->nextToken();
            $n->setOp2($this->paren_expr());
            if ($this->token->getTag() != 'SEMICOLON') {
                $this->errors->set($this->token->getId(), "';' expected");
            }
        } elseif ($this->token->getTag() == 'SEMICOLON') {
            $n = new Node($this->EMPTY, null, null, null, null);
            $this->nextToken();
        } elseif ($this->token->getTag() == 'LBRA') {
            $n = new Node($this->EMPTY, null, null, null, null);
            $this->nextToken();
            while ($this->token->getTag() != 'RBRA') {
                $n = new Node($this->SEQ, null, $n, $this->statement(), null);
            }
            $this->nextToken();
        } else {
            $n = new Node($this->EXPR, null, $this->expr(), null, null);

            if ($this->token->getTag() != 'SEMICOLON') {
                $this->errors->set($this->token->getId(), "';' expected");
            }
            $this->nextToken();
        }
        return $n;
    }

    function paren_expr() {
        if ($this->token->getTag() != 'LPAR') {
            $this->errors->set($this->token->getId(), "'(' expected");
        }
        $this->nextToken();
        $n = $this->expr();


        if ($this->token->getTag() != 'RPAR') {
            $this->errors->set($this->token->getId(), "')' expected");
        }
        $this->nextToken();

        return $n;
    }

    function expr() {
        //Если токен не является переменной, то отправляем его на тест
        if ($this->token->getTag() != 'ID') {
            return $this->test();
        }
        //Если токен является переменной, константой, то отправляем ее в тест
        $n = $this->test();
        if ($n->getKind() == $this->VAR && $this->token->getTag() == 'EQUAL') {
            $this->nextToken();
            $n = new Node($this->SET, null, $n, $this->expr(), null);
        }
        return $n;
    }

    function test() {
        $n = $this->summa();
        if ($this->token->getTag() == 'LESS') {
            $this->nextToken();
            $n = new Node($this->LT, null, $n, $this->summa(), null);
        }
        return $n;
    }

    function summa() {
        //Получили узел с переменной или константой (не конец!!)
        $n = $this->term();

        //Если был плюс или минус то создаем соответствующий узел
        while ($this->token->getTag() == 'PLUS' || $this->token->getTag() == 'MINUS') {
            if ($this->token->getTag() == 'PLUS') {
                $kind = $this->ADD;
            } else {
                $kind = $this->SUB;
            }
            $this->nextToken();
            $n = new Node($kind, null, $n, $this->term(), null);
        }
        return $n;
    }

    function term() {
        //Если токен оказался переменной или числом, то создаем новый узел и возвращаем его
        if ($this->token->getTag() == "ID") {
            $n = new Node($this->VAR, $this->token->getValue(), null, null, null);
            $this->nextToken();
            return $n;
        } elseif ($this->token->getTag() == 'NUM') {
            $n = new Node($this->CONST, $this->token->getValue(), null, null, null);
            $this->nextToken();
            return $n;
        } else {
            //Иначе возвращаемся к скобкам
            return $this->paren_expr();
        }
    }

}

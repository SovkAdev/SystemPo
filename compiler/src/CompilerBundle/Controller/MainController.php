<?php

namespace CompilerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller {

    public function indexAction() {
        return $this->render('CompilerBundle:Site:index.html.twig');
    }

    public function lexerAction() {
        return $this->render('CompilerBundle:Site:lexer.html.twig');
    }

    public function parserAction() {
        return $this->render('CompilerBundle:Site:parser.html.twig');
    }
    
    public function compilerAction() {
        return $this->render('CompilerBundle:Site:compiler.html.twig');
    }

}

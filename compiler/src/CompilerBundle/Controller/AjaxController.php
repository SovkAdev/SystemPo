<?php

namespace CompilerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use CompilerBundle\Model\Token;
use CompilerBundle\Model\Parser;

class AjaxController extends Controller {

    public function lexerGetDataAction(Request $request) {
        if ($request->isMethod('POST')) {

            $data = json_decode($request->getContent(), true);

            $tokens = new ArrayCollection();
            $count = 0;
            foreach ($data as $dataToken) {
                $token = new Token();
                $token->setId(++$count);
                $token->setValue($dataToken['value']);
                $token->setTag($dataToken['tag']);
                $tokens[] = $token;
            }

            return $this->render('CompilerBundle:ajax:lexer.html.twig', ['tokens' => $tokens]);
        }
    }

    public function parserGetDataAction(Request $request) {
        if ($request->isMethod('POST')) {

            $data = json_decode($request->getContent(), true);

            $tokens = new ArrayCollection();
            $count = 0;
            foreach ($data as $dataToken) {
                $token = new Token();
                $token->setId(++$count);
                $token->setValue($dataToken['value']);
                $token->setTag($dataToken['tag']);
                $tokens[] = $token;
            }
            $token = new Token();
            $token->setTag(-3); // EOF
            $tokens->add($token);

            $parser = new Parser();
            $parser->setTokens($tokens);
            $tree = $parser->parse();
            return $this->render('CompilerBundle:ajax:parser.html.twig', ['parser' => $parser, 'tree' => $tree]);
        }
    }

}

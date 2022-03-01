<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function test(): Response
    {
        $page =' <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            <h1>Bonjour</h1>
        </body>
        </html>';
        $rep = new Response($page);
        $json = ["prenom"=>"Ali","nom"=>"Amor"];
        $rep = new JsonResponse($json);
        return $rep;
        /*return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);*/
    }
    /**
     * @Route("/test2", name="app_test2")
     */
    public function test2(): Response
    {
        $nom = "Amor";
        $prenom = "Ali";
        $notes = [12,18,20,5,16,8];
        $rep= $this->render('test/test2.html.twig', ["lastName" => $nom,"firstName" => $prenom, "notes" => $notes ]);
        return $rep;
    }
    /**
     * @Route("/test3/toto", name="app_test3")
     */
    public function test4($id): Response
    {
        
        //$nom = "Amor";
        //$prenom = "Ali";
        //$notes = [12,18,20,5,16,8];
        $rep= $this->render('test/test3.html.twig', ["id" => $id ]);
        return $rep;
    }
    /**
     * @Route("/test3/{id}", name="app_test3")
     */
    public function test3($id): Response
    {
        
        //$nom = "Amor";
        //$prenom = "Ali";
        //$notes = [12,18,20,5,16,8];
        $rep= $this->render('test/test3.html.twig', ["id" => $id ]);
        return $rep;
    }
}

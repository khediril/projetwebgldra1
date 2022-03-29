<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/add/{nom}/{prix}/{stock}", name="app_produit_add")
     */
    public function add($nom,$prix,$stock): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setNom($nom);
        $produit->setPrix($prix);
        $produit->setStock($stock);
        $produit->setDescription("ce produit est bon");
        $produit->setCreatedAt(new \DateTime());
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->render('produit/add.html.twig', ["nom"=>$nom,"prix"=>$prix,"stock"=>$stock
            
        ]);
    }
    /**
     * @Route("/produit/list", name="app_produit_list")
     */
    public function list(): Response
    {
        $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $repos->findAll();
       // dd($produits);
        return $this->render('produit/list.html.twig', ["produits" => $produits]);
    }
     /**
     * @Route("/produit/{id}", name="app_produit_detail")
     */
    public function detail($id): Response
    {
        $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repos->find($id);
      //  $produits = $repos->chercherParIntervallePrix(10,1000);
       // dd($produits);
       return $this->render('produit/detail.html.twig', ["produit" => $produit]);
    }  
     /**
     * @Route("/produit/delete/{id}", name="app_produit_delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repos->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le produit a ete supprimer avec succes'
        );
        return $this->redirectToRoute('app_produit_list');
        //  $produits = $repos->chercherParIntervallePrix(10,1000);
       // dd($produits);
    }
      /**
     * @Route("/produit/update/{id}/{nouvprix}", name="app_produit_update")
     */
    public function update($id,$nouvprix): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $repos->find($id);
        $produit->setPrix($nouvprix);

        $entityManager->persist($produit);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le produit a ete modifie avec succes'
        );
        return $this->redirectToRoute('app_produit_list');
        //  $produits = $repos->chercherParIntervallePrix(10,1000);
       // dd($produits);
    }
}
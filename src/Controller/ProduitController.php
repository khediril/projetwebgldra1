<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    
    private $repos;
    private $doctrine;
    public function __construct(ProduitRepository $repos,ManagerRegistry $doctrine)
    {
       $this->repos = $repos;
       $this->doctrine = $doctrine;
    }
    /**
     * @Route("/produit/add/{nom}/{prix}/{stock}/{idc}", name="app_produit_add")
     */
    public function add($nom,$prix,$stock,$idc,CategorieRepository $reposCateg): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $produit->setNom($nom);
        $produit->setPrix($prix);
        $produit->setStock($stock);
        $produit->setDescription("ce produit est bon");
        $produit->setCreatedAt(new \DateTime());

        $produit->setCategorie($reposCateg->find($idc));
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->render('produit/add.html.twig', ["nom"=>$nom,"prix"=>$prix,"stock"=>$stock
            
        ]);
    }
    /**
     * @Route("/produit/list", name="app_produit_list")
     */
    public function list(Request $request): Response
    {
        $nb = $request->query->get('nb'); //method GET
        //$nb = $request->request->get(nb); //method POST
        // $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $this->repos->findAll();
       // dd($produits);
        return $this->render('produit/list.html.twig', ["produits" => $produits,"nb" =>$nb]);
    }
     /**
     * @Route("/produit/{id}", name="app_produit_detail",requirements={"id"="\d+"})
     */
    public function detail($id): Response
    {
        //$repos = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $this->repos->find($id);
        $categ = $produit->getCategorie();// en cas de besoin
        if (!$produit) {
             throw $this->createNotFoundException('Ce produit est inexistant');
             
            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
         }
             
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
        $repos = $this->doctrine()->getRepository(Produit::class);
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
        $entityManager = $this->doctrine->getManager();
       // $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $this->repos->find($id);
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
    /**
     * @Route("/produit/form", name="app_produit_chercher")
     */
    public function chercher(): Response
    {
        return $this->render('produit/chercher.html.twig', []);
    }
        /**
     * @Route("/produit/chercher", name="app_chercher")
     */
    public function chercherProduit(Request $request): Response
    {
        $id = $request->request->get('id'); //method GET
        //dd($request);
        //$repos = $this->getDoctrine()->getRepository(Produit::class);
        $produit = $this->repos->find($id);
        if (!$produit) {
             throw $this->createNotFoundException('Ce produit est inexistant');
             
            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
         }
             
      //  $produits = $repos->chercherParIntervallePrix(10,1000);
       // dd($produits);
       return $this->render('produit/detail.html.twig', ["produit" => $produit]);
    }  
     /**
     * @Route("/produit/chercher/{pmin}/{pmax}", name="app_chercher_interval_prix")
     */
    public function chercherProduitIntervalPrix(Request $request,$pmin,$pmax): Response
    {
       
        
        //$repos = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $this->repos->findByPriceInterval($pmin,$pmax);
       /* if (!$produit) {
             throw $this->createNotFoundException('Ce produit est inexistant');
             
            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
         }*/
             
      //  $produits = $repos->chercherParIntervallePrix(10,1000);
       // dd($produits);
       return $this->render('produit/rechercheParPrix.html.twig', ["produits" => $produits,"pmin" => $pmin,"pmax" => $pmax]);
    }  


}
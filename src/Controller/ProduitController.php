<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\Produit2Type;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    private $repos;
    private $doctrine;
    public function __construct(ProduitRepository $repos, ManagerRegistry $doctrine)
    {
        $this->repos = $repos;
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/produit/add/{nom}/{prix}/{stock}/{idc}", name="app_produit_add")
     */
    public function add($nom, $prix, $stock, $idc, CategorieRepository $reposCateg): Response
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
     * @Route("/", name="app_produit_list")
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
     * @Route("/prod/{id}", name="app_list_prod_categ")
     */
    public function listProdParCateg($id): Response
    {
        $categorie= $this->doctrine->getRepository(Categorie::class)->find($id);
        // $produits = $categorie->getProduits();
        //$nb = $request->query->get('nb'); //method GET
        //$nb = $request->request->get(nb); //method POST
        // $repos = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $this->repos->findBy(['categorie'=> $categorie]);
        // dd($produits);
        return $this->render('produit/listparCateg.html.twig', ["produits" => $produits,"categorie" =>$categorie]);
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
    public function update($id, $nouvprix): Response
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
    public function chercherProduitIntervalPrix(Request $request, $pmin, $pmax): Response
    {
       
        
        //$repos = $this->getDoctrine()->getRepository(Produit::class);
        $produits = $this->repos->findByPriceInterval($pmin, $pmax);
        /* if (!$produit) {
              throw $this->createNotFoundException('Ce produit est inexistant');

             // the above is just a shortcut for:
             // throw new NotFoundHttpException('The product does not exist');
          }*/
             
        //  $produits = $repos->chercherParIntervallePrix(10,1000);
        // dd($produits);
        return $this->render('produit/rechercheParPrix.html.twig', ["produits" => $produits,"pmin" => $pmin,"pmax" => $pmax]);
    }
    /**
     * @Route("/produit/ajout", name="app_ajout")
     */
    public function ajout(Request $request, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        $produit->setNom('toto');
      /*  $form = $this->createFormBuilder($produit)
                     ->add('nom', TextType::class)
                     ->add('prix', IntegerType::class)
                     ->add('description', TextareaType::class)
                     ->add('stock', IntegerType::class)
                     ->add('categorie', EntityType::class, [
                        // looks for choices from this entity
                        'class' => categorie::class,
                    
                        // uses the User.username property as the visible option string
                        'choice_label' => 'nom',
                    
                        // used to render a select box, check boxes or radios
                        // 'multiple' => true,
                        // 'expanded' => true,
                    ])
                     ->add('save', SubmitType::class, ['label' => 'Cree Produit'])
                     
                     ->getForm();*/
        $form = $this->createForm(Produit2Type::class,$produit);
        
       
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $produit = $form->getData();
            //$agree = $form->get('agreeTerms')->getData(); unmapped field
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setBrochureFilename($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
             
            // ... perform some action, such as saving the task to the database
             
            return $this->redirectToRoute('app_produit_list');
        }
        // return $this->render('produit/ajout.html.twig', ["monform" => $form->createView()]);

        return $this->renderForm('produit/ajout.html.twig', [
                        'monform' => $form,
                    ]);
    }
    /**
     * @Route("/produit/unmappedform", name="app_form")
     */
    public function contact(Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            dd($data);
        }
        return $this->renderForm('produit/form.html.twig', [
            'form' => $form,
        ]);
        // ... render the form
    }
     /**
     * @Route("/produit/search", name="app_search")
     */
    public function search(Request $request): Response
    {
        $ch = $request->get("search");
        $produits=$this->repos->findByName($ch);
        //dd($ch);
        return $this->renderForm('produit/search.html.twig', [
            'produits' => $produits,
        ]);
        // ... render the form
    }
}

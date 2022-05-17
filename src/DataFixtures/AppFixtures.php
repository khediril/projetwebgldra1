<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Comprod;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Categorie;
use App\Entity\Fournisseur;
use Zenstruck\Foundry\faker;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory as Factory2;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
       $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create();
        $faker = Factory2::faker();
        for ($j=0;$j<6;$j++) {
            $categorie = new Categorie();
            $categorie->setNom("categ".$j);
            $manager->persist($categorie);
            $fournisseur = new Fournisseur();
            $fournisseur->setNom("Fournisser".$j)
                        ->setAdresse($faker->address())
                        ->setEmail($faker->email())
                        ->setTelephone($faker->phoneNumber());
            $manager->persist($fournisseur);
            for ($i=0;$i<10;$i++) {
                $produit = new Produit();
                $produit->setNom("produit".$j.$i)
                        ->setPrix($i * 2)
                        ->setStock(10)
                        ->setDescription("Le produit".$i." est bon")
                        ->setCreatedAt(new \DateTime())
                        ->setCategorie($categorie)
                        ->addFournisseur($fournisseur)
                        ->setBrochureFilename('iset.jpeg');
                $manager->persist($produit);
            }
        }
        $manager->flush();
        $produits = $manager->getRepository(Produit::class)->findAll();
        $nb = count($produits);

        for ($i=0;$i<5;$i++) {
            $commande = new Commande();
            $commande->setCreatedAt(new \DateTime())
                     ->setEtat(false);
            $manager->persist($commande);
            for ($j=0;$j<4;$j++) {
                $comprod = new Comprod();
                $produit = $produits[rand(0, $nb-1)];
                $comprod->setCommande($commande)
                        ->setProduit($produit)
                        ->setQuantite(rand(1, 10))
                        ->setPrix($produit->getPrix());
                $manager->persist($comprod);
            }
        }
        for ($i=0;$i<2;$i++) {
            $user = new User();
            $user->setNom('user'.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $hashedPassword = $this->passwordHasher->hashPassword($user,$user->getNom());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

        }
        for ($i=2;$i<4;$i++) {
            $user = new User();
            $user->setNom('user'.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $hashedPassword = $this->passwordHasher->hashPassword($user,$user->getNom());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);

        }

        $manager->flush();
    }
}

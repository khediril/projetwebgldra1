<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comprod;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Categorie;
use App\Entity\Fournisseur;
use Zenstruck\Foundry\faker;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory as Factory2;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
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
                        ->addFournisseur($fournisseur);
                $manager->persist($produit);
            }
        }
        $manager->flush();
        $produits = $manager->getRepository(Produit::class)->findAll();
        $nb = count($produits);

        for($i=0;$i<5;$i++)
        {
            $commande = new Commande();
            $commande->setCreatedAt(new \DateTime())
                     ->setEtat(false);
            $manager->persist($commande);
            for($j=0;$j<4;$j++)
            {
                $comprod = new Comprod();
                $produit = $produits[rand(0,$nb)];
                $comprod->setCommande($commande)
                        ->setProduit($produit)
                        ->setQuantite(rand(1,10))
                        ->setPrix($produit->getPrix());
                $manager->persist($comprod);
            }
        }
        $manager->flush();
    }
}

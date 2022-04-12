<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($j=0;$j<6;$j++) {
            $categorie = new Categorie();
            $categorie->setNom("categ".$j);
            $manager->persist($categorie);
            for ($i=0;$i<10;$i++) {
                $produit = new Produit();
                $produit->setNom("produit".$i)
                        ->setPrix($i * 2)
                        ->setStock(10)
                        ->setDescription("Le produit".$i." est bon")
                        ->setCreatedAt(new \DateTime())
                        ->setCategorie($categorie);
                $manager->persist($produit);
            }
        }

        
        

        $manager->flush();
    }
}

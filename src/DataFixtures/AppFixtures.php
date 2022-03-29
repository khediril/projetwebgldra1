<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0;$i<100;$i++) {
            $produit = new Produit();
            $produit->setNom("produit".$i);
            $produit->setPrix($i * 2);
            $produit->setStock(10);
            $produit->setDescription("Le produit".$i." est bon");
            $produit->setCreatedAt(new \DateTime());
            $manager->persist($produit);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i <=10 ; $i++) { 
            $article = new Articles();
            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("Contenu de l'article n°$i")
                    ->setImage("https://via.placeholder.com/350x150")
                    ->setCreatedAt(new \DateTime());

            $manager->persist($article);
            
        }

        $manager->flush();
    }
}
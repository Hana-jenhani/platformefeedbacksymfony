<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $client= new Client();
            $client->setNom($faker->nom);
            $client->setPrenom($faker->prenom);
            $client->setCin($faker->cin);
            $client->setTel($faker->tel);
            $client->setAdresse($faker->adresse);
            $client->setDatenaissance($faker->datenaissance);
            $manager->persist($client);
        }

        $manager->flush();

     
    }
}

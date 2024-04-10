<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class CountryFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //for ($i = 0; $i < 3; $i++) {
        $country = new Country();
        $country->setName("Germany");
        $manager->persist($country);

        $this->addReference('country', $country);

        $countryFR = new Country();
        $countryFR->setName("France");
        $manager->persist($countryFR);

        //}
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2;
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASONS = [
        'Saison 1' => [
            'number' => 1,
            'description' => 'Saison 1',
            'year' => 2015
        ],
        'Saison 2' => [
            'number' => 2,
            'description' => 'Saison 2',
            'year' => 2016
        ],
        'Saison 3' => [
            'number' => 3,
            'description' => 'Saison 3',
            'year' => 2017
        ],
        'Saison 4' => [
            'number' => 4,
            'description' => 'Saison 4',
            'year' => 2018
        ],
        'Saison 5' => [
            'number' => 5,
            'description' => 'Saison 5',
            'year' => 2019
        ],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (ProgramFixtures::PROGRAMS as $key => $programTitle) {
            foreach (self::SEASONS as $number => $seasonData) {
                $season = new Season();
                $season->setNumber($seasonData['number']);
                $season->setYear($seasonData['year']);
                $season->setDescription($seasonData['description']);
                $season->setProgram($this->getReference('program_' . $key));
                $manager->persist($season);
                $this->addReference('season_' . $key . '_' . $number, $season);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}

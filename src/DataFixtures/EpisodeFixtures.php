<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const EPISODES = [
        [
            'title' => "First",
            'number' => 1,
            'synopsis' => 'Synopsis 1',
        ],
        [
            'title' => "Second",
            'number' => 2,
            'synopsis' => 'Synopsis 2',
        ],
        [
            'title' => "Third",
            'number' => 3,
            'synopsis' => 'Synopsis 3',
        ],
        [
            'title' => "Fourth",
            'number' => 4,
            'synopsis' => 'Synopsis 4',
        ],
        [
            'title' => "Fifth",
            'number' => 5,
            'synopsis' => 'Synopsis 5',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (ProgramFixtures::PROGRAMS as $programTitle => $programData) {
            foreach (SeasonFixtures::SEASONS as $seasonTitle => $seasonData) {
                foreach (self::EPISODES as $number => $episodeData) {
                    $episode = new Episode();
                    $episode->setTitle($episodeData['title']);
                    $episode->setNumber($episodeData['number']);
                    $episode->setSynopsis($episodeData['synopsis']);
                    $episode->setSeason($this->getReference('season_'. $programTitle . '_' . $seasonTitle));
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}

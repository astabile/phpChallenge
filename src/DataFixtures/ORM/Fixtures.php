<?php

namespace App\DataFixtures\ORM;

use App\Entity\User;
use App\Entity\Entry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('admin')
            ->setEmail('alejandrostabile@gmail.com')
            ->setPassword('pass')
            ->setTwitter('john.doe');

        $manager->persist($user);

        $entry = new Entry();
        $entry
            ->setTitle('First blog post example')
            ->setSlug('first-blog-post-example')
            ->setContent('Lorem Ipsum is simply dummy text of the printing and typesetting industry.')
            ->setUser($user);
        $manager->persist($entry);
        $manager->flush();
    }
}

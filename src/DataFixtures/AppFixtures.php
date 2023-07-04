<?php
/**
 * This file is part of the AppFixtures package.
 *
 * (c) Your Name <your.email@example.com>
 *
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * AppFixtures class.
 *
 * This class is responsible for loading initial data fixtures into the database.
 * It should be used to populate the database with test data or default data.
 */
class AppFixtures extends Fixture
{
    /**
     * Flushes the changes made within the ObjectManager.
     *
     * @param ObjectManager $manager the ObjectManager instance to flush changes from
     */
    public function load(ObjectManager $manager): void
    {
        $manager->flush();
    }
}

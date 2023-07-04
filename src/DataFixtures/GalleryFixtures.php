<?php
/**
 * Gallery fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Gallery;

/**
 * Class GalleryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class GalleryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(10, 'galleries', function (int $i) {
            $gallery = new Gallery();
            $gallery->setTitle($this->faker->unique()->word);
            $gallery->setSlug($this->faker->unique()->word);
            $gallery->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $gallery->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $gallery;
        });

        $this->manager->flush();
    }
}

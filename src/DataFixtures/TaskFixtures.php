<?php
/**
 * Task fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Enum\TaskStatus;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class TaskFixtures.
 */
class TaskFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'tasks', function (int $i) {
            $task = new Task();
            $task->setTitle($this->faker->sentence);
            $task->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $task->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            /**
             * @var Category $category
             */
            $category = $this->getRandomReference('categories');
            $task->setCategory($category);

            /** @var User $author */
            $author = $this->getRandomReference('admins');
            $task->setAuthor($author);

            return $task;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class,  UserFixtures::class];
    }
}

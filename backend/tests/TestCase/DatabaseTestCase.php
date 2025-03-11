<?php

namespace App\Tests\TestCase;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;

class DatabaseTestCase extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::$kernel = self::bootKernel();

        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $this->refreshDatabase();
    }

    /**
     * @throws Exception
     */
    private function refreshDatabase(): void
    {
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $application->run(
            new ArrayInput([
                'command' => 'doctrine:schema:drop',
                '--force' => true,
                '--env' => 'test',
                '--quiet' => true,
            ]),
        );

        $application->run(
            new ArrayInput([
                'command' => 'doctrine:migrations:migrate',
                '--env' => 'test',
                '--no-interaction' => true,
                '--quiet' => true,
            ]),
        );

        $application->run(
            new ArrayInput([
                'command' => 'doctrine:fixtures:load',
                '--env' => 'test',
                '--no-interaction' => true,
                '--quiet' => true,
            ]),
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}

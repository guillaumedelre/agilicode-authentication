<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 24/09/18
 * Time: 20:52
 */

namespace App\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\DBAL\Connection;
use Fidry\AliceDataFixtures\LoaderInterface;
use Fidry\AliceDataFixtures\Persistence\PurgeMode;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;

class DbContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var string
     */
    private $fixturesPath;

    /**
     * DbContext constructor.
     *
     * @param KernelInterface   $kernel
     * @param RegistryInterface $doctrine
     * @param LoaderInterface   $loader
     * @param string            $fixturesPath
     */
    public function __construct(
        KernelInterface $kernel,
        RegistryInterface $doctrine,
        LoaderInterface $loader,
        string $fixturesPath
    ) {
        $this->kernel = $kernel;
        $this->doctrine = $doctrine;
        $this->loader = $loader;
        $this->fixturesPath = $fixturesPath;
    }

    /**
     * @BeforeScenario @clearDatabaseBeforeScenario
     */
    public function clearDatabaseBeforeScenario(BeforeScenarioScope $beforeScenarioScope)
    {
        $this->theDatabaseIsClean();
    }

    /**
     * @throws \Exception
     */
    private function drop()
    {
        $this->runCommand("bin/console doctrine:schema:drop --force --full-database");
    }

    /**
     * @throws \Exception
     */
    private function migrate()
    {
        $this->runCommand("bin/console doctrine:migration:migrate --no-interaction");
    }

    /**
     * @throws \Throwable
     */
    private function truncate()
    {
        /** @var Connection $conn */
        $conn = $this->doctrine->getConnection();
        $conn->transactional(
            function (Connection $connection) {
                $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0');
                foreach ($connection->getSchemaManager()->listTableNames() as $tableName) {
                    $connection->executeQuery(sprintf('TRUNCATE TABLE %s', $tableName));
                }
                $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1');
            }
        );
    }

    /**
     * @param string $commandLine
     *
     * @throws \Exception
     */
    private function runCommand(string $commandLine)
    {
        $process = new Process("export APP_ENV={$this->kernel->getEnvironment()} && $commandLine");
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \Exception(
                sprintf(
                    'The command `%s` has failed: `%s`',
                    $process->getCommandLine(),
                    $process->getErrorOutput()
                )
            );
        }
    }

    /**
     * @Given the database is clean
     * @throws \Throwable
     */
    public function theDatabaseIsClean()
    {
        $this->drop();
        $this->migrate();
        $this->truncate();
    }

    /**
     * @Given the following fixtures are loaded:
     */
    public function theFollowingFixturesAreLoaded(PyStringNode $pyStringNode)
    {
        $this->theDatabaseIsClean();
        if (!empty($pyStringNode->getStrings())) {
            $this->loader->load(
                array_map(
                    function ($fixtureFile) {
                        return $this->fixturesPath . trim($fixtureFile);
                    }, $pyStringNode->getStrings()
                ),
                [],
                [],
                new PurgeMode(0)
            );
        }
    }
}
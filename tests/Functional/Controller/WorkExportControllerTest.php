<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class WorkExportControllerTest extends JsonApiTestCase
{
    private const SCENARIO_1 = 'scenario-1';
    private const SCENARIO_2 = 'scenario-2';
    private const SCENARIO_3 = 'scenario-3';
    private const SCENARIO_4 = 'scenario-4';


    protected function setUp(): void
    {
        parent::setUp();
        $this->auth();
    }

    public function testScenario1()
    {
        $this->runAndAssertScenario(self::SCENARIO_1);
    }

//    public function testScenario2()
//    {
//        $this->runAndAssertScenario(self::SCENARIO_2);
//    }
//
    public function testScenario3()
    {
        $this->runAndAssertScenario(self::SCENARIO_3);
    }

    public function testScenario4()
    {
        $this->runAndAssertScenario(self::SCENARIO_4);
    }


    private function runAndAssertScenario(string $scenarioName)
    {
        $this->postScenarioToEndpoint($scenarioName);
        $response = $this->cli->getResponse();

        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJsonStringEqualsJsonFile(
            $this->getScenarioOutput($scenarioName),
            $response->getContent(),
            );
    }


    private function postScenarioToEndpoint(string $scenarioFolder): void
    {
        $body = file_get_contents(__DIR__ . "/../../Json/EmployeeExport/{$scenarioFolder}/input.json");
        $this->postJson('/api/exports/employee', $body);
    }

    private function getScenarioOutput(string $scenarioFolder): string
    {
        return __DIR__ . "/../../Json/EmployeeExport/{$scenarioFolder}/output.json";
    }

}
<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

abstract class JsonApiTestCase extends WebTestCase
{
    public const DEFAULT_EMAIL = 'admin@user.com';

    public const DEFAULT_PASS = 'aaaaa';

    /** @var KernelBrowser|null */
    protected $cli;

    /** @var string|null */
    private $token;


    protected function setUp(): void
    {
        $this->cli = static::createClient();
    }

    protected function tearDown(): void
    {
        $this->cli = null;
        $this->token = null;
    }


    protected function auth(string $username = self::DEFAULT_EMAIL, string $password = self::DEFAULT_PASS): void
    {
        $this->post('/api/login_check', [
            'username' => $username ?? self::DEFAULT_EMAIL,
            'password' => $password ?? self::DEFAULT_PASS,
        ]);

        if ($this->cli->getResponse()->getStatusCode() !== Response::HTTP_OK) {
            throw new BadCredentialsException("Authentication failed, check your credentials or fixtures.");
        }

        $response = json_decode($this->cli->getResponse()->getContent(), true);

        $this->token = $response['token'];
    }

    protected function logout(): void
    {
        $this->token = null;
    }

    protected function post(string $uri, array $params): void
    {
        $this->postJson($uri, (string)json_encode($params));
    }

    protected function postJson(string $uri, string $body): void
    {
        $this->cli->request(
            'POST',
            $uri,
            [],
            [],
            $this->headers(),
            $body
        );
    }

    protected function get(string $uri, array $parameters = []): void
    {
        $this->cli->request(
            'GET',
            $uri,
            $parameters,
            [],
            $this->headers()
        );
    }

    private function headers(): array
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($this->token) {
            $headers['HTTP_Authorization'] = 'Bearer ' . $this->token;
        }

        return $headers;
    }
}

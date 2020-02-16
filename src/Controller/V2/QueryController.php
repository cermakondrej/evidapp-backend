<?php

declare(strict_types=1);

namespace App\Controller\V2;

use EvidApp\Shared\Application\Query\Collection;
use EvidApp\Shared\Application\Query\Item;
use EvidApp\Shared\Infrastructure\Bus\QueryBus;
use EvidApp\Shared\Infrastructure\Bus\QueryInterface;
use App\Response\JsonFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class QueryController
{
    private const CACHE_MAX_AGE = 31536000; // Year.

    /** @var JsonFormatter */
    private $formatter;

    /** @var QueryBus */
    private $queryBus;

    /** @var UrlGeneratorInterface */
    private $router;

    public function __construct(QueryBus $queryBus, JsonFormatter $formatter, UrlGeneratorInterface $router)
    {
        $this->queryBus = $queryBus;
        $this->formatter = $formatter;
        $this->router = $router;
    }

    protected function ask(QueryInterface $query)
    {
        return $this->queryBus->handle($query);
    }

    protected function jsonCollection(Collection $collection, bool $isImmutable = false): JsonResponse
    {
        $response = JsonResponse::create($this->formatter::collection($collection));

        $this->decorateWithCache($response, $collection, $isImmutable);

        return $response;
    }

    protected function json(Item $resource): JsonResponse
    {
        return JsonResponse::create($this->formatter->one($resource));
    }

    protected function route(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

    private function decorateWithCache(JsonResponse $response, Collection $collection, bool $isImmutable): void
    {
        if ($isImmutable && $collection->limit === \count($collection->data)) {
            $response
                ->setMaxAge(self::CACHE_MAX_AGE)
                ->setSharedMaxAge(self::CACHE_MAX_AGE);
        }
    }
}
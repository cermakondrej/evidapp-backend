<?php

declare(strict_types=1);

namespace App\Response;

use EvidApp\Shared\Application\Query\Collection;
use EvidApp\Shared\Application\Query\Item;

final class JsonFormatter
{
    public static function one(Item $resource): array
    {
        return array_filter([
            'data' => self::model($resource),
            'relationships' => self::relations($resource->relationships),
        ]);
    }

    public static function collection(Collection $collection): array
    {
        $transformer = function ($data) {
            return $data instanceof Item ? self::model($data) : $data;
        };

        $resources = array_map($transformer, $collection->data);

        return array_filter([
            'meta' => [
                'size' => $collection->limit,
                'page' => $collection->page,
                'total' => $collection->total,
            ],
            'items' => $resources,
        ]);
    }

    private static function model(Item $resource): array
    {
        return [
            'id' => $resource->id,
            'type' => $resource->type,
            'attributes' => $resource->resource,
        ];
    }

    private static function relations(array $relations): array
    {
        $result = [];

        /** @var Item $relation */
        foreach ($relations as $relation) {
            $result[$relation->type] = [
                'data' => self::model($relation),
            ];
        }

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace EvidApp\Shared\Application\Query;

use Broadway\ReadModel\SerializableReadModel;

final class Item
{
    public string $id;
    public string $type;
    public array $resource;
    public array $relationships = [];
    public SerializableReadModel $readModel;

    public function __construct(SerializableReadModel $serializableReadModel, array $relations = [])
    {
        $this->id = $serializableReadModel->getId();
        $this->type = $this->type($serializableReadModel);
        $this->resource = $serializableReadModel->serialize();
        $this->relationships = $relations;
        $this->readModel = $serializableReadModel;
    }

    private function type(SerializableReadModel $model): string
    {
        $path = explode('\\', \get_class($model));

        return array_pop($path);
    }
}
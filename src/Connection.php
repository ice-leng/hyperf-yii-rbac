<?php

namespace Lengbin\Hyperf\YiiSoft\Rbac;

use Hyperf\DbConnection\Db;
use Lengbin\YiiSoft\Rbac\ConnectionInterface;

class Connection implements ConnectionInterface
{

    public function selectOne(string $query, array $bindings = [])
    {
        return Db::selectOne($query, $bindings);
    }

    public function select(string $query, array $bindings = []): array
    {
        return Db::select($query, $bindings);
    }

    public function insert(string $query, array $bindings = []): bool
    {
        return Db::insert($query, $bindings);
    }

    public function update(string $query, array $bindings = []): int
    {
        return Db::update($query, $bindings);
    }

    public function delete(string $query, array $bindings = []): int
    {
        return Db::delete($query, $bindings);
    }
}

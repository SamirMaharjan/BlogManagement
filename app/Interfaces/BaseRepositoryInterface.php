<?php


namespace App\Interfaces;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function get_all(array $filters = []): Collection|array;

    public function get_one(int $id): Model|Collection|Builder|array|null;

    public function get_by_field(string $key, string $value): Model|Builder|null;

    public function paginated_list(int $limit, array $filters = []);

    public function create(array $data): Model|Builder;

    public function update(array $data, int $id): Model|Builder|bool;

    public function delete(int $id): bool;

    public function count(): int;

    public function count_where(string $key, string $value): int;

    public function get_active_one(int $id): Model|null;

    public function paginated_list_with_relation(int $limit, array $relation, array $filters = []);

}

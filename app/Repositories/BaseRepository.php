<?php


namespace App\Repositories;


use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class BaseRepository implements BaseRepositoryInterface
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function getQueries(array $filters = []): Builder
    {
        $query = $this->model::query();
        $this->filterContent($query, $filters);
        return $query;
    }

    protected function getQueriesWithModel(array $filters = [], Model $model): Builder
    {
        $query = $model::query();
        $this->filterContent($query, $filters);
        return $query;
    }

    protected function getQueriesWithRelation(array $relations, array $filters = []): Builder
    {
        
        $query = $this->model::query()->with($relations);
        log::info($relations);
        log::info($query->tosql());
        $this->filterContentWithRelation($query, $relations, $filters);
        return $query;
    }
    protected function getPlanQueriesWithRelation(array $relations, array $filters = []): Builder
    {
        $query = $this->model::query()->with($relations);
        log::info($relations);
        log::info($query->tosql());
        $this->filterPlanContentWithRelation($query, $relations, $filters);
        return $query;
    }

    protected function filterContent($query, array $filters = []): void
    {
        //todo add filter options
        return;
    }

    protected function filterContentWithRelation($query, array $relations, array $filters = []): void
    {
        //todo add filter options
        return;
    }
    protected function filterPlanContentWithRelation($query, array $relations, array $filters = []): void
    {
        //todo add filter options
        return;
    }

    public function get_all(array $filters = []): Collection|array
    {
        return $this->getQueries($filters)->get();
    }

    public function get_one(int $id): Model|Collection|Builder|array|null
    {
        return $this->model::query()->find($id);
    }

    public function paginated_list(int $limit, array $filters = []): LengthAwarePaginator
    {
        // Log::info($this->getQueries($filters)->toSql());
        return $this->getQueries($filters)
            ->orderBy('id', 'DESC')
            ->paginate($limit)
            ->appends($filters);
    }

    public function paginated_list_with_relation(int $limit, array $relations, array $filters = []): LengthAwarePaginator
    {
        return $this->getQueriesWithRelation($relations, $filters)
            ->orderBy('id', 'DESC')
            ->paginate($limit)
            ->appends($filters);
    }

    public function create(array $data): Model|Builder
    {
        return $this->model::query()->create($data);
    }

    public function createWithModel(array $data, Model $model): Model|Builder
    {
        return $model::query()->create($data);
    }

    public function update(array $data, int $id): Model|bool
    {
        /**
         * @var $model Model
         */
        $model = $this->get_one($id);
        if ($model) {
            $model->update($data);
            return $model;
        } else {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        /**
         * @var $model Model
         */
        $model = $this->get_one($id);
        if ($model) {
            $model->delete();
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @return Model|Builder|null
     */
    public function get_by_field(string $key, string $value): Model|Builder|null
    {
        return $this->model::query()->where($key, '=', $value)->first();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->model::query()->count();
    }

    /**
     * @param string $key
     * @param string $value
     * @return int
     */
    public function count_where(string $key, string $value): int
    {
        return $this->model::query()
            ->where($key, '=', $value)
            ->count();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function get_active_one(int $id): Model|null
    {
        return $this->model::query()
            ->where('id', '=', $id)
            ->where('is_active', true)
            ->first();
    }


}

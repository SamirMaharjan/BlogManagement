<?php

namespace App\Services;

use App\Repositories\BaseRepository;

class BaseService
{

    public function __construct(private $repository)
    {
    }

    public function findById($id)
    {
        return $this->repository->get_one($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function findByField($key, $value)
    {
        return $this->repository->get_by_field($key, $value);
    }

    public function findActiveById($id)
    {
        return $this->repository->get_active_one($id);
    }

    public function update($data,$id){
        return $this->repository->update($data,$id);
    }
}

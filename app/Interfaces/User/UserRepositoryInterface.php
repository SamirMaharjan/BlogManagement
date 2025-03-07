<?php

namespace App\Interfaces\User;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Interfaces\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function index(): string;
    public function paginated_list(int $limit, array $filters = []);
    public function addUser(CreateUserRequest $validatedData);
    public function updateUser(UpdateUserRequest $validatedData);
    public function viewUser($id);
    public function deleteUser($id);
   
}
<?php

namespace App\Repositories\User;


use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    private $User;
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->User = $model;
    }

    public function index(): string
    {
        return $this->model->index();
    }


    protected function filterContent($query, array $filters = []): void
    {
        $validSortOptions = ['name', 'email', 'created_at','updated_at'];

        if (isset($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'LIKE', '%' . $filters['search'] . '%')->orWhere('email', 'LIKE', '%' . $filters['search'] . '%');
            });
        }
        if (isset($filters['name'])) {
            $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }
        if (isset($filters['email'])) {
            $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
        }
        if (isset($filters['sort'])) {
            if ($filters['sort'] === 'asc') {
                if (isset($filters['sort_by'])&&in_array($filters['sort_by'], $validSortOptions)) {
                    $query->orderBy($filters['sort_by'], 'asc');
                }else{
                    $query->orderBy('name', 'asc');
                }
            } elseif ($filters['sort'] === 'desc') {
                if (isset($filters['sort_by'])&&in_array($filters['sort_by'], $validSortOptions)) {
                    $query->orderBy($filters['sort_by'], 'desc');
                }else{
                    $query->orderBy('name', 'desc');
                }
            }
        }
       
    
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {
            $query->whereBetween('created_at', [$filters['fromDate'], $filters['toDate']]);
        } elseif (isset($filters['fromDate'])) {
            $query->whereDate('created_at', '>=', $filters['fromDate']);
        } elseif (isset($filters['toDate'])) {
            $query->whereDate('created_at', '<=', $filters['toDate']);
        }
        $query->select(['id', 'name', 'email', 'created_at', 'updated_at']);
    }
    public function addUser(CreateUserRequest $newValidatedData)
    {

        $user = User::create([
            'name' => $newValidatedData->name,
            'email' => $newValidatedData->email,
            'password' => Hash::make($newValidatedData->password),
        ]);
        // dd($user);
        // $user->save();

    }
    public function updateUser(UpdateUserRequest $newValidatedData)
    {
        // Find the user
        $user = User::findOrFail($newValidatedData->id);

        // Prepare an array of fields to update
        $data = [
            'name' => $newValidatedData->name,
            'email' => $newValidatedData->email,
        ];

        // Conditionally add the password if it exists in the request
        if ($newValidatedData->filled('password')) {
            $data['password'] = Hash::make($newValidatedData->password);
        }

        // Update the user with the data
        $user->update($data);
    }
    public function viewUser($id){
       $User = User::where('id',$id)->select(['id', 'name', 'description', 'levels_id'])->first();
       return $User;
    }
    public function deleteUser($id){
        $User = User::findorfail($id);
        $User->delete();
    }
  

}

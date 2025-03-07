<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $UserService;
    private  $agent;
    public function __construct(UserService $UserService, User $agent)
    {
        $this->UserService = $UserService;
        $this->agent = $agent;
    }

    public function list(Request $request): Response
    {
        
        return $this->UserService->paginatedList($request->query->get('per_page') ?? 10, $request->query->all());
    }
    public function userList(Request $request)
    {

        $response = $this->UserService->processForBlade($request->query->get('per_page') ?? 10, $request->query->all());
        if ($response) {
            return view('users.index2', compact('response'));
        }

        return redirect()->back()->with('error', 'Failed to fetch users.');
    }
    // public function createUser(Request $request)
    // {
    //     return view('users.create');
    // }
    public function storeUser(CreateUserRequest $request)
    {
        return $this->UserService->addUser($request);
    }
    public function updateUser(UpdateUserRequest $request): Response
    {
        // $request['agent_id'] = $this->agent->getLoggedInAgentsId();
        return $this->UserService->updateUser($request);
    }
    public function deleteUser($id){
        return $this->UserService->deleteUser($id);
     }
    public function getUser(Request $request): Response
    {
        return $this->UserService->paginatedList($request->query->get('per_page') ?? 10, $request->query->all());
    }
    
    
     public function viewUser($id){
        return $this->UserService->viewUser($id);
     }
    
}
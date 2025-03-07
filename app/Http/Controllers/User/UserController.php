<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V2\CommissionItem\CommissionItemRequest;
use App\Http\Requests\Api\V2\CommissionItem\AssignPlanRequest;
use App\Http\Requests\Api\V2\Contract\AssignAgentRequest;
use App\Http\Requests\Api\V2\Contract\ContractRequest;
use App\Http\Requests\Api\V2\Contract\ContractUpdateRequest;
use App\Models\User;
use App\Models\User\Agent;
use App\Models\UserAuthentication;
use App\Models\V2\commissions\Contract;
use App\Services\Commission\ListCommissionService;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function storeUser(Request $request)
    {
        try {
            // dd($request->all());
            $this->UserService->addUser($request);

            return redirect()->route('users.index')->with('success', 'User added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add user: ' . $e->getMessage());
        }
    }
    public function getUser(Request $request): Response
    {
        return $this->UserService->paginatedList($request->query->get('per_page') ?? 10, $request->query->all());
    }
    
    public function updateUser(UserUpdateRequest $request): Response
    {
        // $request['agent_id'] = $this->agent->getLoggedInAgentsId();
        $userAuth = new UserAuthentication();
        $request['agent_id'] = $userAuth->getUser()->user->id;
        return $this->UserService->updateUser($request);
    }
     public function viewUser($id){
        return $this->UserService->viewUser($id);
     }
     public function deleteUser($id){
        return $this->UserService->deleteUser($id);
     }
     public function editCommissionItem($id){
        return $this->UserService->editCommissionItem($id);
     }
     public function updateCommissionItem(CommissionItemRequest $request){
         $data = $request['data'];
         $User_id = $request["User_id"];
         $userAuth = new UserAuthentication();
         $request['user_id'] = $userAuth->getUser()->user->id;

         $User_type = User::findorfail($User_id);
         return $this->UserService->updateCommissionItem($request);
       
     }

     public function assignAgent(AssignAgentRequest $request){
        return $this->UserService->assignAgent($request);
     }
     public function getLevel(){
        return $this->UserService->getLevel();
     }
     public function dropDownList(Request $request){
      return $this->UserService->dropDownList($request->query->get('query'));
     }
     public function commissiondropDownList($id,Request $request){
      return $this->UserService->commissiondropDownList($id,$request->query->get('query'));
     }
     public function getCommissionItemMastersheet($id){
      return $this->UserService->getCommissionItemMastersheet($id);
     }

    public function assignPlan(AssignPlanRequest $request): HttpResponse
    {
        $validatedData = $request->validated();
        return $this->UserService->assignPlan($validatedData);
    }
}
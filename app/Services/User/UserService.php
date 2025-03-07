<?php

namespace App\Services\User;


use App\Http\Resources\User\UserResource;
use App\Interfaces\User\UserRepositoryInterface;
use App\Services\BaseService;
use App\Traits\Paginator;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Service Layer
 */
class UserService extends BaseService
{
    use ResponseMessage, Paginator;

    private  $UserInterface;
    public function __construct(UserRepositoryInterface $UserInterface)
    {
        // parent::__construct($this->UserInterface);
        $this->UserInterface = $UserInterface;
    }

    public function paginatedList($limit, $filters = []): Response
    {
        $paginatedData = $this->UserInterface->paginated_list($limit, $filters);
        if ($paginatedData->isEmpty()) return $this->successResponse('User Type is Empty', [], Response::HTTP_NO_CONTENT);
        // dd($paginatedData);
        $result = [];
        foreach ($paginatedData as $d) {
            $result[] = new UserResource($d);
        }
        // dd($result);
        return $this->successResponse(
            'User Type Fetched Successfully',
            [
                'data' => $result,
                'links' => $this->links($paginatedData),
                'meta' => $this->meta($paginatedData)
            ],
            Response::HTTP_OK
        );
    }

    public function processForBlade($limit, $filters = [])
    {
        $paginatedData = $this->UserInterface->paginated_list($limit, $filters);
        // Return a LengthAwarePaginator for Blade view
        return $paginatedData;
    }
    public function addUser(Request $newValidatedData): Response
    {
        // dd($newValidatedData);
        try {
            DB::transaction(function () use ($newValidatedData) {
                $this->UserInterface->addUser($newValidatedData);
            });
        } catch (\Exception $e) {
            return $this->failedResponse(
                $message = $e->getMessage(),
                $code = Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->successResponse(
            $message = "User added successfully",
            $code = Response::HTTP_OK
        );
    }
    public function updateUser(Request $newValidatedData): Response
    {
        // dd($newValidatedData);
        try {
            DB::transaction(function () use ($newValidatedData) {
                $this->UserInterface->updateUser($newValidatedData);
            });
        } catch (\Exception $e) {
            return $this->failedResponse(
                $message = $e->getMessage(),
                $code = Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->successResponse(
            $message = "User Type Updated successfully",
            $code = Response::HTTP_OK
        );
    }

    public function viewUser($id)
    {
        $contratData = $this->UserInterface->viewUser($id);
        // 
        if ($contratData == null) {
            // dd(!($contratData));
            return $this->failedResponse('User Type not Found', 404);
        }
        $result = [];

        return $this->successResponse(
            'User Type Fetched Successfully',
            [
                'data' => $contratData,
            ],
            Response::HTTP_OK
        );
    }
    public function deleteUser($id)
    {
        try {
            DB::transaction(function () use ($id) {
                return $this->UserInterface->deleteUser($id);
            });
        } catch (\Exception $e) {
            return $this->failedResponse(
                $message = $e->getMessage(),
                $code = Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->successResponse(
            $message = "User Deleted successfully",
            $code = Response::HTTP_OK
        );
    }

}

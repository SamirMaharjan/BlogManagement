<?php

namespace App\Services\Blog;


use App\Http\Resources\Blog\BlogResource;
use App\Interfaces\Blog\BlogRepositoryInterface;
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
class BlogService extends BaseService
{
    use ResponseMessage, Paginator;

    private  $BlogInterface;
    public function __construct(BlogRepositoryInterface $BlogInterface)
    {
        // parent::__construct($this->BlogInterface);
        $this->BlogInterface = $BlogInterface;
    }

    public function paginatedList($limit, $filters = []): Response
    {
        $paginatedData = $this->BlogInterface->paginated_list($limit, $filters);
        if ($paginatedData->isEmpty()) return $this->successResponse('Blog is Empty', [], Response::HTTP_NO_CONTENT);
        // dd($paginatedData);
        $result = [];
        foreach ($paginatedData as $d) {
            $result[] = new BlogResource($d);
        }
        // dd($result);
        return $this->successResponse(
            'Blog Fetched Successfully',
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
        $paginatedData = $this->BlogInterface->paginated_list($limit, $filters);
        // Return a LengthAwarePaginator for Blade view
        return $paginatedData;
    }
    public function addBlog(Request $newValidatedData): Response
    {
        // dd($newValidatedData);
        try {
            DB::transaction(function () use ($newValidatedData) {
                $this->BlogInterface->addBlog($newValidatedData);
            });
        } catch (\Exception $e) {
            return $this->failedResponse(
                $message = $e->getMessage(),
                $code = Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->successResponse(
            $message = "Blog added successfully",
            $code = Response::HTTP_OK
        );
    }
    public function updateBlog(Request $newValidatedData): Response
    {
        // dd($newValidatedData);
        try {
            DB::transaction(function () use ($newValidatedData) {
                $this->BlogInterface->updateBlog($newValidatedData);
            });
        } catch (\Exception $e) {
            return $this->failedResponse(
                $message = $e->getMessage(),
                $code = Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->successResponse(
            $message = "Blog Updated successfully",
            $code = Response::HTTP_OK
        );
    }

    public function viewBlog($id)
    {
        $contratData = $this->BlogInterface->viewBlog($id);
        // 
        if ($contratData == null) {
            // dd(!($contratData));
            return $this->failedResponse('Blog not Found', 404);
        }
        $result = [];

        return $this->successResponse(
            'Blog Fetched Successfully',
            [
                'data' => $contratData,
            ],
            Response::HTTP_OK
        );
    }
    public function deleteBlog($id)
    {
        try {
            DB::transaction(function () use ($id) {
                return $this->BlogInterface->deleteBlog($id);
            });
        } catch (\Exception $e) {
            return $this->failedResponse(
                $message = $e->getMessage(),
                $code = Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->successResponse(
            $message = "Blog Deleted successfully",
            $code = Response::HTTP_OK
        );
    }

}

<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\CreateBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use App\Services\Blog\BlogService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    private $BlogService;
    private  $agent;
    public function __construct(BlogService $BlogService, Blog $agent)
    {
        $this->BlogService = $BlogService;
        $this->agent = $agent;
    }

    public function list(Request $request): Response
    {
        
        return $this->BlogService->paginatedList($request->query->get('per_page') ?? 10, $request->query->all());
    }
    public function userList(Request $request)
    {

        $response = $this->BlogService->processForBlade($request->query->get('per_page') ?? 10, $request->query->all());
        if ($response) {
            return view('users.index2', compact('response'));
        }

        return redirect()->back()->with('error', 'Failed to fetch users.');
    }
    // public function createBlog(Request $request)
    // {
    //     return view('users.create');
    // }
    public function storeBlog(CreateBlogRequest $request)
    {
        // dd($request->all());
        return $this->BlogService->addBlog($request);
    }
    public function updateBlog(UpdateBlogRequest $request): Response
    {
        // $request['agent_id'] = $this->agent->getLoggedInAgentsId();
        return $this->BlogService->updateBlog($request);
    }
    public function deleteBlog($id){
        return $this->BlogService->deleteBlog($id);
     }
    public function getBlog(Request $request): Response
    {
        return $this->BlogService->paginatedList($request->query->get('per_page') ?? 10, $request->query->all());
    }
    
    
     public function viewBlog($id){
        return $this->BlogService->viewBlog($id);
     }
    
}
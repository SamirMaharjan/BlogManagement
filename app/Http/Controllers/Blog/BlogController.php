<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogAuthentication;
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
    public function blogList(Request $request)
    {

        $response = $this->BlogService->processForBlade($request->query->get('per_page') ?? 10, $request->query->all());
        if ($response) {
            dd($response);
            return view('blogs.index2', compact('response'));
        }

        return redirect()->back()->with('error', 'Failed to fetch blogs.');
    }
    // public function createBlog(Request $request)
    // {
    //     return view('blogs.create');
    // }
    public function storeBlog(Request $request)
    {
        try {
            // dd($request->all());
            $this->BlogService->addBlog($request);

            return redirect()->route('blogs.index')->with('success', 'Blog added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add blog: ' . $e->getMessage());
        }
    }
    public function getBlog(Request $request): Response
    {
        return $this->BlogService->paginatedList($request->query->get('per_page') ?? 10, $request->query->all());
    }
    
    public function updateBlog(BlogUpdateRequest $request): Response
    {
        // $request['agent_id'] = $this->agent->getLoggedInAgentsId();
        $blogAuth = new BlogAuthentication();
        $request['agent_id'] = $blogAuth->getBlog()->blog->id;
        return $this->BlogService->updateBlog($request);
    }
     public function viewBlog($id){
        return $this->BlogService->viewBlog($id);
     }
     public function deleteBlog($id){
        return $this->BlogService->deleteBlog($id);
     }
  
}
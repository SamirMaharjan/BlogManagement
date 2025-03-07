<?php

namespace App\Interfaces\Blog;

use App\Http\Requests\Blog\CreateBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Interfaces\BaseRepositoryInterface;

interface BlogRepositoryInterface extends BaseRepositoryInterface
{
    public function index(): string;
    public function paginated_list(int $limit, array $filters = []);
    public function addBlog(CreateBlogRequest $validatedData);
    public function updateBlog(UpdateBlogRequest $validatedData);
    public function viewBlog($id);
    public function deleteBlog($id);
   
}
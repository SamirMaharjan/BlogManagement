<?php

namespace App\Repositories\Blog;


use App\Http\Requests\Blog\CreateBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Interfaces\Blog\BlogRepositoryInterface;
use App\Models\Blog;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BlogRepository extends BaseRepository implements BlogRepositoryInterface
{

    private $Blog;
    public function __construct(Blog $model)
    {
        parent::__construct($model);
        $this->Blog = $model;
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
        $query->with('images')->select(['id', 'title', 'content', 'slug','created_at', 'updated_at']);
    }
    public function addBlog(CreateBlogRequest $newValidatedData)
    {

        $blog = Blog::create([
            'title' => $newValidatedData['title'],
            'slug' => $newValidatedData['title'],
            'user_id' => auth()->user()->id,
            'content' => $newValidatedData['content']
        ]);

        if ($newValidatedData->hasFile('image')) {
            // dd($newValidatedData->image);
            // foreach ($newValidatedData->file('image') as $image) {
            // dd($image);
            // $imagePath = $image->store('blogs', 'public');
            $imagePath = Storage::disk('public')->put('blogs', $newValidatedData->image);

            // Attach multiple images
            $blog->images()->create(['path' => $imagePath, 'file_name' => $blog->title . rand(1, 100)]);
        }
    }
    public function updateBlog(UpdateBlogRequest $newValidatedData)
    {
        // Find the blog post
        $blog = Blog::findOrFail($newValidatedData->id);

        // Update blog details
        $blog->update([
            'title' => $newValidatedData['title'],
            'slug' => $newValidatedData['title'],
            'content' => $newValidatedData['content']
        ]);

        // Handle new image upload
        if ($newValidatedData->hasFile('image')) {
            // Store the new image
            $imagePath = Storage::disk('public')->put('blogs', $newValidatedData->image);

            // Save new image record
            $blog->images()->create([
                'path' => $imagePath,
                'file_name' => $blog->title . rand(1, 100)
            ]);
        }

    // Remove images if requested
    if ($newValidatedData->filled('remove_images')) {
        foreach ($newValidatedData->remove_images as $imageId) {
            $image = $blog->images()->find($imageId);
            if ($image) {
                // Delete image from storage
                Storage::disk('public')->delete($image->path);
                // Remove from database
                $image->delete();
            }
        }
    }

    // return response()->json(['message' => 'Blog updated successfully!', 'blog' => $blog]);
}
    public function viewBlog($id){
       $Blog = Blog::where('id',$id)->with('images')->first();
       return $Blog;
    }
    public function deleteBlog($id){
        $Blog = Blog::findorfail($id);
        $Blog->delete();
    }
  

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PostsController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    public function index()
    {
        $all_posts = $this->post->withTrashed()->latest()->paginate(10);
        $all_categories = $this->category->all();

        $selected_categories = [];
        foreach($this->post->categoryPost as $category_post)
        {
            $selected_categories[] = $category_post->category_id;
        }

        return view('admin.posts.index')
                ->with('all_posts', $all_posts)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);
    }

    public function hide($id)
    {
        $this->post->destroy($id);
        return redirect()->back();
    }

    public function unhide($id)
    {
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

}

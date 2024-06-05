<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        // $posts = Post::query()
        //         ->where('active', '=', 1)
        //         ->whereDate('published_at', '<', Carbon::now())
        //         ->orderBy('published_at', 'desc')
        //         ->paginate(2);


        $latestPost = Post::where('active', '=', 1)
        ->whereDate('published_at', '<', Carbon::now())
        ->orderBy('published_at', 'desc')
        ->limit(1)
        ->first();

        $popularPost = Post::query()
            ->leftJoin('upvote_downvotes', 'posts.id', '=', 'upvote_downvotes.post_id')
            ->select('posts.*', DB::raw('COUNT(upvote_downvotes.id) as upvote_count'))
            ->where(function($query){
                $query->whereNull('upvote_downvotes.is_upvote')
                ->orWhere('upvote_downvotes.is_upvote', '=', 1);
            })
            ->where('active', '=', 1)
            ->whereDate('published_at', '<', Carbon::now())
            ->orderByDesc('upvote_count')
            ->groupBy('posts.id')
            ->limit(3)
            ->get();

            $user = auth()->user();

            if($user){
                $leftJoin = "(SELECT cp.category_id, cp.post_id FROM upvote_downvotes
                            JOIN category_post cp ON upvote_downvotes.post_id = cp.post_id
                            WHERE upvote_downvotes.is_upvote = 1 and upvote_downvotes.user_id = ?) as t";
                            
                  $recommendedPost =  Post::query()
                  ->leftJoin('category_post as cp', 'posts.id', '=', 'cp.post_id')
                  ->leftJoin(DB::raw($leftJoin), function($join){
                    $join->on('t.category_id', '=', 'cp.category_id')
                        ->on('t.post_id', '<>', 'cp.post_id');
                  }) 
                  ->select('posts.*')
                  ->where('posts.id', '<>', DB::raw('t.post_id'))
                  ->setBindings([$user->id])
                  ->limit(3)
                  ->get();
            }else{
                
        $recommendedPost = Post::query()
        ->leftJoin('post_views', 'posts.id', '=', 'post_views.post_id')
        ->select('posts.*', DB::raw('COUNT(post_views.id) as view_count'))
        ->where('active', '=', 1)
        ->whereDate('published_at', '<', Carbon::now())
        ->orderByDesc('view_count')
        ->groupBy('posts.id')
        ->limit(5)
        ->get();
            }


        $categories = Category::query()
            // ->with(['posts' => function($query){
            //     $query->orderByDesc('published_at');
            // }])

            ->whereHas('posts', function($query){
                $query->where('active', '=', 1)
                ->whereDate('published_at', '<', Carbon::now());
            })
            ->select('categories.*')
            ->selectRaw('MAX(posts.published_at) as max_date')
            ->leftJoin('category_post', 'categories.id', '=', 'category_post.category_id')
            ->leftJoin('posts', 'posts.id', '=', 'category_post.post_id')
            ->orderByDesc('max_date')
            ->groupBy('categories.id')
            ->limit(5)
            ->get();

        return view('home', compact('latestPost', 'popularPost', 'recommendedPost', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Request $request)
    {
        if(!$post->active || $post->published_at> Carbon::now()){
            throw new NotFoundHttpException();
        }

        $next = Post::query()->where('active', '=', true)
                ->whereDate('published_at', '<=', Carbon::now())
                ->whereDate('published_at', '<', $post->published_at)
                ->orderBy('published_at', 'desc')
                ->limit(1)
                ->first();
        $prev = Post::query()->where('active', '=', true)
                ->whereDate('published_at', '<=', Carbon::now())
                ->whereDate('published_at', '>', $post->published_at)
                ->orderBy('published_at', 'asc')
                ->limit(1)
                ->first();

        $user = $request->user();

        PostView::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'post_id' => $post->id,
            'user_id' => $user?->id,
        ]);

        return view('post.view', compact('post', 'prev', 'next'));
    }


    public function byCategory(Category $category)
    {
        $posts = Post::query()
        ->join('category_post', 'posts.id', '=', 'category_post.post_id')
        ->where('category_post.category_id', '=', $category->id)
        ->where('active', '=', true)
        ->whereDate('published_at', '<=', Carbon::now())
        ->orderBy('published_at', 'desc')
        ->paginate(10);

        return view('post.index', compact('posts', 'category'));
    }
    public function seacrh(Request $request)
    {
        $q = $request->get('q');

        $posts = Post::query()
        ->where('active', '=', true)
        ->whereDate('published_at', '<=', Carbon::now())
        ->orderBy('published_at', 'desc')
        ->where(function($query) use($q) {
            $query->where('title', 'like', "%$q%")
        ->orWhere('body', 'like', "%$q%");
        })
        ->paginate(10);

        return view('post.seacrh', compact('posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

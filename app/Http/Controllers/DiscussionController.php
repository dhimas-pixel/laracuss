<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discussion\StoreRequest;
use App\Http\Requests\Discussion\UpdateRequest;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // load semua discussion
        // eager load relasi
        // apakah ada request data search
        // jika ada maka load discussion dengan kata kunci title & content yg nilainya seperti nilai search
        // return page index & data
        // data nya di sort sesuai created_at, pagination per 10 / 20
        // data category
        $discussions = Discussion::with('user', 'category');

        if ($request->search) {
            $discussions = $discussions->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        return response()->view('pages.discussions.index', [
            'discussions' => $discussions->orderBy('created_at', 'desc')->paginate(10)->withQueryString(),
            'categories' => Category::all(),
            'search' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('pages.discussions.form', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        // Get data form request yang tervalidasi
        // Get data category berdasarkan slug
        // Get Id category
        // Input user Id ke array validated 
        // title: create validation laravel
        // slug: create-validation-laravel
        // tambahkan slug + timestamp berdasarkan title ke array validated
        // buat content_preview berdasarkan content
        // bersihkan content dari tag
        // cek content lebih dari 120 karakter
        // jika iya masukan content tersebut ke content preview + '...'
        // jika tidak masukan content tersebut ke content preview + tanpa '...'
        // baru input data ke table
        // jika berhasil kirim notif lalu redirect ke list discussion
        // jika tidak kembalikan error 500

        $validated = $request->validated();
        $categoryId = Category::where('slug', $validated['category_slug'])->first()->id;

        $validated['category_id'] = $categoryId;
        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        $stripContent = strip_tags($validated['content']);
        $isContentLong = strlen($stripContent) > 120;
        $validated['content_preview'] = $isContentLong ? substr($stripContent, 0, 120) . '...' : $stripContent;

        $create = Discussion::create($validated);

        if ($create) {
            session()->flash('notif.success', 'Discussion created successfully!');
            return redirect()->route('discussions.index');
        }

        return abort(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $discussions = Discussion::with(['user', 'category'])->where('slug', $slug)->first();

        if (!$discussions) {
            return abort(404);
        }

        $discussionsAnswers = Answer::where('discussion_id', $discussions->id)->orderBy('created_at', 'desc')->paginate(5);

        $notLikedImage = url('assets/images/like.png');
        $LikedImage = url('assets/images/liked.png');

        return response()->view('pages.discussions.show', [
            'discussion' => $discussions,
            'categories' => Category::all(),
            'likedImage' => $LikedImage,
            'notLikedImage' => $notLikedImage,
            'discussionsAnswers' => $discussionsAnswers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        // get data berdasarkan slug
        // cek data tersebut
        // jika tidak ada return 404
        // jika ada lanjut
        // cek apakah discussion ini milik user yang sedang login
        // jika bukan return 404
        // jika iya return view

        $discussions = Discussion::with('category')->where('slug', $slug)->first();

        if (!$discussions) {
            return abort(404);
        }

        $isOwnedByUser = $discussions->user_id == auth()->id();

        if (!$isOwnedByUser) {
            return abort(404);
        }

        return response()->view('pages.discussions.form', [
            'discussion' => $discussions,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $slug)
    {
        $discussions = Discussion::with('category')->where('slug', $slug)->first();

        if (!$discussions) {
            return abort(404);
        }

        $isOwnedByUser = $discussions->user_id == auth()->id();

        if (!$isOwnedByUser) {
            return abort(404);
        }

        $validated = $request->validated();
        $categoryId = Category::where('slug', $validated['category_slug'])->first()->id;

        $validated['category_id'] = $categoryId;
        $validated['user_id'] = auth()->id();

        $stripContent = strip_tags($validated['content']);
        $isContentLong = strlen($stripContent) > 120;
        $validated['content_preview'] = $isContentLong ? substr($stripContent, 0, 120) . '...' : $stripContent;

        $update =  $discussions->update($validated);

        if ($update) {
            session()->flash('notif.success', 'Discussion updated successfully!');
            return redirect()->route('discussions.show', $slug);
        }

        return abort(500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $discussions = Discussion::with('category')->where('slug', $slug)->first();

        if (!$discussions) {
            return abort(404);
        }

        $isOwnedByUser = $discussions->user_id == auth()->id();

        if (!$isOwnedByUser) {
            return abort(404);
        }

        $delete = $discussions->delete();

        if ($delete) {
            session()->flash('notif.success', 'Discussion deleted successfully!');
            return redirect()->route('discussions.index');
        }

        return abort(500);
    }
}

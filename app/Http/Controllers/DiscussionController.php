<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discussion\StoreRequest;
use App\Models\Category;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class DiscussionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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

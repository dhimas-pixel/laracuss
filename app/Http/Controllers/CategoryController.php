<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Discussion;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($categorySlug)
    {
        // get category berdasarkan slug
        // cek apakah cattegory ada
        // jika tidak ada maka return abort 404
        // query discussion, eager load user & category, get category berdasarkan id
        // discussion sort by created_at menurun dan pagination 10
        // lalu return view

        $category = Category::where('slug', $categorySlug)->first();

        if (!$category) {
            return abort(404);
        }

        $discussions = Discussion::with([
            'user', 'category'
        ])->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return response()->view('pages.discussions.index', [
            'categories' => Category::all(),
            'discussions' => $discussions,
            'withCategory' => $category,
        ]);
    }
}

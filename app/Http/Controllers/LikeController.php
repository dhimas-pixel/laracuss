<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Discussion;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function discussionLike(String $discussionSlug)
    {
        // get discussion berdasarkan slug parameter
        // like discussion dengan model tadi
        // return json
        // isi json dengan like_count

        $discussion = Discussion::where('slug', $discussionSlug)->first();
        $discussion->like();

        return response()->json([
            'status' => 'success',
            'data' => [
                'likeCount' => $discussion->likeCount,
            ]
        ]);
    }

    public function discussionUnlike(String $discussionSlug)
    {
        $discussion = Discussion::where('slug', $discussionSlug)->first();
        $discussion->unlike();

        return response()->json([
            'status' => 'success',
            'data' => [
                'likeCount' => $discussion->likeCount,
            ]
        ]);
    }

    public function answerLike(string $answerId)
    {
        $answer = Answer::find($answerId);

        $answer->like();

        return response()->json([
            'status' => 'success',
            'data' => [
                'likeCount' => $answer->likeCount,
            ]
        ]);
    }

    public function answerUnlike(string $answerId)
    {
        $answer = Answer::find($answerId);

        $answer->unlike();

        return response()->json([
            'status' => 'success',
            'data' => [
                'likeCount' => $answer->likeCount,
            ]
        ]);
    }
}

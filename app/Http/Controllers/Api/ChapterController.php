<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chapter\ChapterStoreRequest;
use App\Http\Requests\Chapter\ChapterUpdateRequest;
use App\Http\Resources\Chapter\ChapterIndexResource;
use App\Http\Resources\Chapter\ChapterShowResource;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all chapters
        return ChapterIndexResource::collection(Chapter::info()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChapterStoreRequest $request)
    {
        // create new chapter
        Chapter::create([
            'book_id'       => $request->book_id,
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'content'       => $request->content,
            'date_release'  => $request->date_release,
            'rating'        => 0,
            'total_reviews' => 0,
        ]);

        // return success message
        $response = ['message' => "Chapter create success"];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // find chapter by slug
        $chapter = Chapter::info()->findBySlug($slug);

        // if chapter doesnt exist return error message
        if (!$chapter) return response()->json(['message' => 'Chapter does not exist']);

        // return data
        return new ChapterShowResource($chapter);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(ChapterUpdateRequest $request, $slug)
    {
        // find chapter by slug
        $chapter = Chapter::info()->find($slug);

        // if chapter doesnt exist return error message
        if (!$chapter) return response()->json(['message' => 'Chapter does not exist']);

        // update chapter with request data
        $chapter->slug             = null;
        $chapter->title            = $request->title;
        $chapter->content          = $request->content;
        $chapter->date_release     = $request->date_release;

        // save new chapter data
        $chapter->save();

        // return success message
        $response = ['message' => "Book update success"];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        // find chapter by slug
        $chapter = Chapter::info()->findBySlug($slug);

        // if chapter doesnt exist return error message
        if (!$chapter) return response()->json(['message' => 'Chapter does not exist']);

        // delete relationship from this chapter
        $chapter->reviews()->delete();

        // delete chapter
        $chapter->delete();

        // return success message
        $response = ['message' => "Chapter delete success"];
        return response()->json($response);
    }
}

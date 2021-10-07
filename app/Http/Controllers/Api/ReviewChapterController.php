<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewChapter\ReviewChapterStoreRequest;
use App\Http\Requests\ReviewChapter\ReviewChapterUpdateRequest;
use App\Http\Resources\ReviewChapter\ReviewChapterIndexResource;
use App\Http\Resources\ReviewChapter\ReviewChapterShowResource;
use App\Models\Chapter;
use App\Models\ReviewChapter;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all book's reviews
        return ReviewChapterIndexResource::collection(ReviewChapter::info()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewChapterStoreRequest $request, $slug)
    {
        // find chapter by chapter_id
        $chapter = Chapter::findBySlug($slug);

        // if chapter doesnt exist return error message
        if ($chapter) {
            // get review where chapter_id is the same as request->chapter_id
            // and user_id is the same as the logged in user id
            $existingReview = ReviewChapter::where([
                ['chapter_id',  '=', $chapter->id],
                ['user_id',  '=', $request->user_id]
            ])->get();

            // check if existingReview already exist
            // if not then send an error message
            if ($existingReview->count() < 1) {
                // create review
                ReviewChapter::create([
                    'chapter_id'           => $chapter->id,
                    'user_id'           => $request->user_id,
                    'user_name'         => $request->username,
                    'rating'            => $request->rating,
                    'user_comment'      => $request->comment 
                ]);

                // get collection of reviews where chapter_id is the same as request->chapter_id
                $reviews = ReviewChapter::where('chapter_id', $chapter->id)->get();
        
                // update chapter overall total_reviews and rating columns data
                $chapter->total_reviews = $reviews->count();
                $chapter->rating        = $reviews->avg('rating');
        
                // save chapter data into database
                $chapter->save();
            } else { 
                // return error message
                throw new Error('You reviewed this chapter already. Only one review per customer is allowed!', 1);
            }
        }

        // return success message
        $response = ['message', 'Review create success'];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // find review by id
        $review = ReviewChapter::info()->find($id);

        // if review doesnt exist return error message
        if (!$review) return response()->json(['message' => 'Review does not exist']);

        // return data
        return new ReviewChapterShowResource($review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ReviewChapterUpdateRequest $request, $id)
    {
        // find review by id
        $review = ReviewChapter::find($id);

        // if review doesnt exist return error message
        if (!$review) return response()->json(['message' => 'Review does not exist..']);

        // get logged in user id
        $user_id = Auth::id();

        // if no user is logged in return error message
        if (!$user_id) return response()->json(['message' => 'Unable to find logged in user..']);

        // find user by logged in id
        $user = User::find($user_id);

        // if user doesnt exist return error message
        if (!$user) return response()->json(['message' => 'User does not exist..']);

        // update review data
        $review->admin_name     = $user->name;
        $review->user_comment   = $request->user_comment;
        $review->admin_comment  = $request->admin_comment;

        // save the new review data
        $review->save();

        // return success message
        $response = ['message', 'Review update success'];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find review by id
        $review = ReviewChapter::info()->find($id);

        // if review doesnt exist return error message
        if (!$review) return response()->json(['message' => 'Review does not exist']);
        
        // delete review
        $review->delete();

        // return success message
        $response = ['message' => "Review delete success"];
        return response()->json($response);
    }
}

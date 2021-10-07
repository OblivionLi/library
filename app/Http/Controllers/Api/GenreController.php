<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Genre\GenreStoreRequest;
use App\Http\Requests\Genre\GenreUpdateRequest;
use App\Http\Resources\Genre\GenreIndexResource;
use App\Http\Resources\Genre\GenreShowResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all genres
        return GenreIndexResource::collection(Genre::info()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GenreStoreRequest $request)
    {
        // create new genre
        Genre::create([
            'name'      => $request->name,
        ]);

        // return success message
        $response = ['message' => "Genre create success"];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // find genre by id
        $genre = Genre::info()->find($id);

        // if genre doesnt exist return error message
        if (!$genre) return response()->json(['message' => 'Genre does not exist']);

        // return data
        return new GenreShowResource($genre);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GenreUpdateRequest $request, $id)
    {
        // find genre by id
        $genre = Genre::info()->find($id);

        // if genre doesnt exist return error message
        if (!$genre) return response()->json(['message' => 'Genre does not exist']);

        // update genre with request data
        $genre->name     = $request->name;

        // save new genre data
        $genre->save();

        // return success message
        $response = ['message' => "Genre update success"];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find genre by id
        $genre = Genre::info()->find($id);

        // if genre doesnt exist return error message
        if (!$genre) return response()->json(['message' => 'Genre does not exist']);

        // detach relationship from this genre
        $genre->books()->detach();

        // delete genre
        $genre->delete();

        // return success message
        $response = ['message' => "Genre delete success"];
        return response()->json($response);
    }
}

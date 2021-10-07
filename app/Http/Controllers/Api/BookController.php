<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookStoreRequest;
use App\Http\Requests\Book\BookUpdateRequest;
use App\Http\Resources\Book\BookIndexResource;
use App\Http\Resources\Book\BookShowResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return collection of all books
        return BookIndexResource::collection(Book::info()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookStoreRequest $request)
    {
        // create new book
        Book::create([
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'author'        => $request->author,
            'translator'    => $request->translator,
            'status'        => $request->status,
            'description'   => $request->description,
            'date_release'  => $request->date_release,
            'rating'        => 0,
            'total_reviews' => 0,
            'cover'         => $request->file('cover') ? $request->file('cover')->store('covers', 'public') : Storage::url('placeholder.jpeg')
        ]);

        // return success message
        $response = ['message' => "Book create success"];
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
        // find book by slug
        $book = Book::info()->findBySlug($slug);

        // if book doesnt exist return error message
        if (!$book) return response()->json(['message' => 'Book does not exist']);

        // return data
        return new BookShowResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(BookUpdateRequest $request, $slug)
    {
        // find book by slug
        $book = Book::info()->find($slug);

        // if book doesnt exist return error message
        if (!$book) return response()->json(['message' => 'Book does not exist']);

        // update book with request data
        $book->slug             = null;
        $book->title            = $request->title;
        $book->author           = $request->author;
        $book->translator       = $request->translator;
        $book->status           = $request->status;
        $book->description      = $request->description;
        $book->date_release     = $request->date_release;

        // get book cover path
        $filePath = public_path('/storage/' . $book->cover);

        // replace book cover if it exists in the request 
        if (!$request->hasFile('cover')) {
            $request->except(['cover']);
        } else {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $book->cover = $request->file('cover')->store('covers', 'public');
        }

        // save new book data
        $book->save();

        // sync book's genres relationship data
        $book->genres()->sync($request->genres);

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
        // find book by slug
        $book = Book::info()->findBySlug($slug);

        // if book doesnt exist return error message
        if (!$book) return response()->json(['message' => 'Book does not exist']);

        $filePath = public_path('/storage/' . $book->cover);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // delete relationship from this book
        $book->chapters()->delete();
        $book->reviews()->delete();

        // detach relationship from this book
        $book->genres()->detach();

        // delete book
        $book->delete();

        // return success message
        $response = ['message' => "Book delete success"];
        return response()->json($response);
    }
}

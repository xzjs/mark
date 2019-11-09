<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all()->toArray();
        foreach ($books as &$book) {
            $book['legends'] = array_map([$this, 'appendHost'], json_decode($book['legends']));
        }
        return response()->json($books);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        Log::notice('store book', [
            'request' => $request->all(),
            'user' => Auth::user(),
            'ip' => $request->getClientIp(),
        ]);
        $request->validate([
            'topic' => 'required|max:255',
        ]);
        $book = new Book();
        $book->topic = $request->input('topic');
        $book->legends = json_encode($request->input('legends'));
        $book->saveOrFail();
        return response()->json($book->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        $book->legends = array_map([$this, 'appendHost'], json_decode($book->legends));
        return response()->json($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::notice('update book', [
            'request' => $request->all(),
            'user' => Auth::user(),
            'ip' => $request->getClientIp(),
        ]);
        $request->validate([
            'topic' => 'required|max:255',
        ]);
        $book = Book::find($id);
        $book->topic = $request->topic;
        $book->legends = json_encode($request->legends);
        $book->saveOrFail();
        return response('success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Book::destroy($id);
        return response('success');
    }
}

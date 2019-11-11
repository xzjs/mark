<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $books = [];
        if ($user->can('book.read')) {
            if ($request->field) {
                $books = Book::select($request->field)->get()->toArray();
            } else {
                $books = Book::paginate(10)->toArray();
                foreach ($books['data'] as &$book) {
                    $book['legends'] = array_map([$this, 'appendHost'], json_decode($book['legends']));
                }
            }
        }
        return response()->json($books);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
        $user = Auth::user();
        if ($user->can('book.write')) {
            $book = new Book();
            $book->topic = $request->input('topic');
            $book->legends = json_encode($request->input('legends'));
            $book->saveOrFail();
            return response('success');
        }
        return response('没有权限', 403);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
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
     * @return Response
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
     * @return Response
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
     * @return Response
     */
    public function destroy($id)
    {
        Book::destroy($id);
        return response('success');
    }
}

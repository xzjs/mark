<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        switch ($user->type) {
            case 0:
                $books = Book::with('users:name')
                    ->get()
                    ->toArray();
                break;
            case 1:
                $books = $user->books;
                break;
            default:
                $books = [];
                break;
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
        $request->validate([
            'name' => 'required|max:255',
            'path' => 'required|max:255',
        ]);
        $book = new Book();
        $book->name = $request->name;
        $book->path = $request->path;
        $book->saveOrFail();
        $users = User::select('id')
            ->where('type', 1)
            ->get()
            ->toArray();
        $userKeys = array_rand($users, 2);
        foreach ($userKeys as $userKey) {
            $userids[]=$users[$userKey]['id'];
        }
        $book->users()->attach($userids);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    private $host;

    public function __construct()
    {
        $this->host = config('services.qiniu.host');
    }

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
            $book['answers'] = array_map([$this, 'appendHost'], json_decode($book['answers']));
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
            'topic' => 'required|max:255',
            'legends' => 'required|array',
            'answers' => 'required|array',
        ]);
        $book = new Book();
        $book->topic = $request->input('topic');
        $book->legends = json_encode($request->input('legends'));
        $book->answers = json_encode($request->input('answers'));
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

    /**
     * 拼接图片路径
     * @param $img
     * @return string
     */
    private function appendHost($img)
    {
        return $this->host . $img . '-small';
    }
}

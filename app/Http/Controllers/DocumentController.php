<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $documents = Document::where('user_id', $id)
            ->select('id', 'title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
        return response()->json($documents);
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
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'introduce' => 'required|max:255',
            'img' => 'required|max:255',
            'answer' => 'required|max:255',
            'tip' => 'required|max:255',
            'subject' => 'required|integer|min:0|max:2',
            'think' => 'required|integer|min:0|max:3',
            'think_difficulty' => 'required|integer|min:0|max:2',
            'ability' => 'required|integer|min:0|max:2',
            'ability_difficulty' => 'required|integer|min:0|max:2',
            'knowledge' => 'required|integer|min:0|max:1',
            'knowledge_difficulty' => 'required|integer|min:0|max:2',
            'place' => 'required|max:255',
            'scene' => 'required|max:255',
            'character' => 'required|max:255',
            'tool' => 'required|max:255',
            'problem' => 'required|integer|min:0|max:5',
            'result' => 'required|integer|min:0|max:2'
        ]);

        $document = new Document();
        $document->title = $request->input('title');
        $document->introduce = $request->input('introduce');
        $document->img = $request->input('img');
        $document->answer = $request->input('answer');
        $document->tip = $request->input('tip');
        $document->subject = $request->input('subject');
        $document->think = $request->input('think');
        $document->think_difficulty = $request->input('think_difficulty');
        $document->ability = $request->input('ability');
        $document->ability_difficulty = $request->input('ability_difficulty');
        $document->knowledge = $request->input('knowledge');
        $document->knowledge_difficulty = $request->input('knowledge_difficulty');
        $document->place = $request->input('place');
        $document->scene = $request->input('scene');
        $document->character = $request->input('character');
        $document->tool = $request->input('tool');
        $document->problem = $request->input('problem');
        $document->result = $request->input('result');
        $document->user_id = Auth::user()->id;
        $document->saveOrFail();
        return response()->json($document->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::findOrFail($id);
        return response()->json($document);
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

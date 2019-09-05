<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->type === 0) {
            $documents = Document::with('book:id,name')
                ->latest()
                ->get()
                ->toArray();
        } else {
            $documents = Document::with('book:id,name')
                ->where('user_id', $user->id)
                ->latest()
                ->get()
                ->toArray();
        }
        if ($documents) {
            foreach ($documents as $key => $document) {
                $documents[$key] = $this->deserialize($document);
            }
        }
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
        Log::notice($request->all());
        $request->validate([
            'book_id' => 'required',
            'pageNumber' => 'required',
            'introduce' => 'required|max:255',
            'img' => 'required',
            'supplementImages' => 'array',
            'answer' => 'required',
            'tip' => 'required',
            'subject' => 'required|array',
            'think' => 'required|array',
            'ability' => 'required|array',
            'knowledge' => 'required|array',
            'place' => 'required|max:255',
            'scene' => 'required|max:255',
            'character' => 'required|max:255',
            'tool' => 'required|max:255',
            'problem' => 'required|max:255',
            'result' => 'required|max:255',
            'start' => 'required|integer',
        ]);

        $document = new Document();
        $document->book_id = $request->input('book_id');
        $document->pageNumber = $request->input('pageNumber');
        $document->introduce = $request->input('introduce');
        $document->img = $request->input('img');
        $document->supplementImages = json_encode($request->input('supplementImages'));
        $document->answer = $request->input('answer');
        $document->tip = $request->input('tip');
        $document->subject = json_encode($request->input('subject'));
        $document->think = json_encode($request->input('think'));
        $document->ability = json_encode($request->input('ability'));
        $document->knowledge = json_encode($request->input('knowledge'));
        $document->place = $request->input('place');
        $document->scene = $request->input('scene');
        $document->character = $request->input('character');
        $document->tool = $request->input('tool');
        $document->problem = $request->input('problem');
        $document->result = $request->input('result');
        $document->user_id = Auth::user()->id;
        $document->cost = (time() - $request->input('start') / 1000);
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
        $request->validate([
            'book_id' => 'required',
            'pageNumber' => 'required',
            'introduce' => 'required|max:255',
            'img' => 'required',
            'supplementImages' => 'required|array',
            'answer' => 'required',
            'tip' => 'required',
            'subject' => 'required|array',
            'think' => 'required|array',
            'ability' => 'required|array',
            'knowledge' => 'required|array',
            'place' => 'required|max:255',
            'scene' => 'required|max:255',
            'character' => 'required|max:255',
            'tool' => 'required|max:255',
            'problem' => 'required|max:255',
            'result' => 'required|max:255',
            'start' => 'required|integer',
        ]);

        $document = Document::find($id);
        $document->book_id = $request->input('book_id');
        $document->pageNumber = $request->input('pageNumber');
        $document->introduce = $request->input('introduce');
        $document->img = $request->input('img');
        $document->supplementImages = json_encode($request->input('supplementImages'));
        $document->answer = $request->input('answer');
        $document->tip = $request->input('tip');
        $document->subject = json_encode($request->input('subject'));
        $document->think = json_encode($request->input('think'));
        $document->ability = json_encode($request->input('ability'));
        $document->knowledge = json_encode($request->input('knowledge'));
        $document->place = $request->input('place');
        $document->scene = $request->input('scene');
        $document->character = $request->input('character');
        $document->tool = $request->input('tool');
        $document->problem = $request->input('problem');
        $document->result = $request->input('result');
        $document->user_id = Auth::user()->id;
        $document->cost = $document->cost + (time() - $request->input('start') / 1000);
        $document->saveOrFail();
        return response()->json($document->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Document::destroy($id);
        if ($count != 1) {
            return response(['message' => '删除失败'], 500);
        } else {
            return response('成功');
        }
    }

    /**
     * 反序列化
     * @param $document
     * @return mixed
     */
    protected function deserialize($document)
    {
        $document['supplementImages'] = json_decode($document['supplementImages'], true);
        $document['subject'] = json_decode($document['subject'], true);
        $document['think'] = json_decode($document['think'], true);
        $document['ability'] = json_decode($document['ability'], true);
        $document['knowledge'] = json_decode($document['knowledge'], true);
        return $document;
    }
}

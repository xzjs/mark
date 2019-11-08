<?php

namespace App\Http\Controllers;

use App\MathMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MathMarkController extends Controller
{
//    public function __construct()
//    {
//        parent::__construct();
//        $this->authorizeResource(MathMark::class);
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $mathMarks = MathMark::with('user:id,name')->get()->toArray();
        } else {
            $mathMarks = MathMark::with('user:id,name')->whereIn('user_id', [1, $user->id])->get()->toArray();
        }
        foreach ($mathMarks as &$mathMark) {
            $mathMark['answers'] = array_map([$this, 'appendHost'], json_decode($mathMark['answers']));
            $mathMark['scene'] = json_decode($mathMark['scene']);
            $mathMark['point'] = json_decode($mathMark['point']);
        }
        return response()->json($mathMarks);
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
        Log::notice('store mathmark', [
            'request' => $request->all(),
            'user' => Auth::user(),
            'ip' => $request->getClientIp()
        ]);
        $request->validate([
            'book_id' => 'required|integer',
            'answers' => 'required|array',
            'scene' => 'required|array',
            'communication' => 'required',
            'strategy' => 'required',
            'mathematicization' => 'required',
            'symbol' => 'required',
            'representation' => 'required',
            'reasoning' => 'required',
            'knowledge' => 'required',
            'point' => 'required|array',
            'start' => 'required',
        ]);

        $mathMark = new MathMark();
        $mathMark->book_id = $request->book_id;
        $mathMark->answers = json_encode($request->answers);
        $mathMark->scene = json_encode($request->scene);
        $mathMark->communication = $request->communication;
        $mathMark->strategy = $request->strategy;
        $mathMark->mathematicization = $request->mathematicization;
        $mathMark->symbol = $request->symbol;
        $mathMark->representation = $request->representation;
        $mathMark->reasoning = $request->reasoning;
        $mathMark->knowledge = $request->knowledge;
        $mathMark->point = json_encode($request->point);
        $mathMark->cost = (time() - $request->input('start') / 1000);
        $mathMark->user_id = Auth::user()->id;

        $mathMark->saveOrFail();
        return response()->json($mathMark->id);
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $mathMark = MathMark::find($id);
        $this->authorize('delete', $mathMark);
        MathMark::destroy($id);
        return response('success');
    }
}

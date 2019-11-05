<?php

namespace App\Http\Controllers;

use App\MathMark;
use Illuminate\Http\Request;

class MathMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mathMarks = MathMark::all()->toArray();
        foreach ($mathMarks as &$mathMark) {
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
        $request->validate([
            'book_id' => 'required|integer',
            'scene' => 'required|array',
            'communication' => 'required',
            'strategy' => 'required',
            'mathematicization' => 'required',
            'symbol' => 'required',
            'representation' => 'required',
            'reasoning' => 'required',
            'knowledge' => 'required',
            'point' => 'required|array',
        ]);

        $mathMark = new MathMark();
        $mathMark->book_id = $request->book_id;
        $mathMark->scene = json_encode($request->scene);
        $mathMark->communication = $request->communication;
        $mathMark->strategy = $request->strategy;
        $mathMark->mathematicization = $request->mathematicization;
        $mathMark->symbol = $request->symbol;
        $mathMark->representation = $request->representation;
        $mathMark->reasoning = $request->reasoning;
        $mathMark->knowledge = $request->knowledge;
        $mathMark->point = json_encode($request->point);

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
     */
    public function destroy($id)
    {
        //
    }
}

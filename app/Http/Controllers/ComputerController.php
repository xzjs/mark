<?php

namespace App\Http\Controllers;

use App\Computer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ComputerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('管理员')) {//是管理员
            $computers = Computer::with('user:id,name')->paginate(10)->toArray();
        } else {
            $computers = Computer::with('user:id,name')->whereIn('user_id', [1, $user->id])->paginate(10)->toArray();
        }
        foreach ($computers['data'] as &$computer) {
            $computer['images'] = array_map([$this, 'appendHost'], json_decode($computer['images']));
            $computer['programThink'] = json_decode($computer['programThink']);
            $computer['point'] = json_decode($computer['point']);
        }
        return response()->json($computers);
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
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function store(Request $request)
    {
        Log::notice('store computer', [
            'request' => $request->all(),
            'user' => Auth::user()->toArray(),
            'ip' => $request->getClientIp(),
            'cookie' => $request->cookies->all(),
        ]);

        $user = Auth::user();
        if (!$user->can('mark.cs')) {
            return response('没有权限', 403);
        }

        $request->validate([
            'point' => 'required|array',
            'images' => 'nullable|array',
            'target' => 'required',
            'contents' => 'required',

            'communication' => 'required',
            'strategy' => 'required',
            'mathematicization' => 'required',
            'symbol' => 'required',
            'representation' => 'required',
            'reasoning' => 'required',

            'abstract' => 'required',
            'summarize' => 'required',
            'disassemble' => 'required',
            'assessment' => 'required',

            'distinguish' => 'required',
            'understand' => 'required',
            'improvement' => 'required',
            'transformation' => 'required',

            'programThink' => 'required',
            'start' => 'required',
        ]);

        if (is_null($request->url) && is_null($request->images)) {
            return response('url和图片不可全为空', 400);
        }

        $computer = new Computer();
        $computer->url = $request->url;
        $computer->images = json_encode($request->images);
        $computer->point = json_encode($request->point);
        $computer->target = $request->target;
        $computer->contents = $request->contents;

        $computer->communication = $request->communication;
        $computer->strategy = $request->strategy;
        $computer->mathematicization = $request->mathematicization;
        $computer->symbol = $request->symbol;
        $computer->representation = $request->representation;
        $computer->reasoning = $request->reasoning;

        $computer->abstract = $request->abstract;
        $computer->summarize = $request->summarize;
        $computer->disassemble = $request->disassemble;
        $computer->assessment = $request->assessment;

        $computer->distinguish = $request->distinguish;
        $computer->understand = $request->understand;
        $computer->improvement = $request->improvement;
        $computer->transformation = $request->transformation;

        $computer->programThink = json_encode($request->programThink);
        $computer->cost = (time() - $request->input('start') / 1000);
        $computer->user_id = Auth::user()->id;

        $computer->saveOrFail();
        return response('success');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        Log::notice('update computer', [
            'request' => $request->all(),
            'user' => Auth::user(),
            'ip' => $request->getClientIp()
        ]);

        $user = Auth::user();
        if (!$user->can('mark.cs')) {
            return response('没有权限', 403);
        }

        $request->validate([
            'point' => 'required|array',
            'images' => 'nullable|array',
            'target' => 'required',
            'contents' => 'required',

            'communication' => 'required',
            'strategy' => 'required',
            'mathematicization' => 'required',
            'symbol' => 'required',
            'representation' => 'required',
            'reasoning' => 'required',

            'abstract' => 'required',
            'summarize' => 'required',
            'disassemble' => 'required',
            'assessment' => 'required',

            'distinguish' => 'required',
            'understand' => 'required',
            'improvement' => 'required',
            'transformation' => 'required',

            'programThink' => 'required',
            'start' => 'required',
        ]);

        if (is_null($request->url) && is_null($request->images)) {
            return response('url和图片不可全为空', 400);
        }

        $computer = Computer::find($id);
        $computer->url = $request->url;
        $computer->images = json_encode($request->images);
        $computer->point = json_encode($request->point);
        $computer->target = $request->target;
        $computer->contents = $request->contents;

        $computer->communication = $request->communication;
        $computer->strategy = $request->strategy;
        $computer->mathematicization = $request->mathematicization;
        $computer->symbol = $request->symbol;
        $computer->representation = $request->representation;
        $computer->reasoning = $request->reasoning;

        $computer->abstract = $request->abstract;
        $computer->summarize = $request->summarize;
        $computer->disassemble = $request->disassemble;
        $computer->assessment = $request->assessment;

        $computer->distinguish = $request->distinguish;
        $computer->understand = $request->understand;
        $computer->improvement = $request->improvement;
        $computer->transformation = $request->transformation;

        $computer->programThink = json_encode($request->programThink);
        $computer->cost += (time() - $request->input('start') / 1000);

        $computer->saveOrFail();
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
        //
    }
}

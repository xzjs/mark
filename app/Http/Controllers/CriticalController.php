<?php

namespace App\Http\Controllers;

use App\Critical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CriticalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('管理员')) {//是管理员
            $criticals = Critical::with('user:id,name')->paginate(10)->toArray();
        } else {
            $criticals = Critical::with('user:id,name')->whereIn('user_id', [1, $user->id])->paginate(10)->toArray();
        }
        foreach ($criticals['data'] as &$critical) {
            $critical['images'] = array_map([$this, 'appendHost'], json_decode($critical['images']));
        }
        return response()->json($criticals);
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
        Log::notice('store critical', [
            'request' => $request->all(),
            'user' => Auth::user()->toArray(),
            'ip' => $request->getClientIp(),
            'cookie' => $request->cookies->all(),
        ]);

        $user = Auth::user();
        if (!$user->can('mark.cr')) {
            return response('没有权限', 403);
        }

        $request->validate([
            'analysis' => 'required',
            'start' => 'required',
        ]);

        if (is_null($request->text) && is_null($request->images)) {
            return response('文字和图片不可全为空', 400);
        }

        $critical = new Critical();
        $critical->text = $request->text ?? '';
        $critical->images = is_null($request->images) ? '[]' : json_encode($request->images);
        $critical->analysis = $request->analysis;
        $critical->user_id = Auth::user()->id;
        $critical->cost = (time() - $request->start / 1000);
        $critical->saveOrFail();

        return response('success');
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
        Log::notice('update critical', [
            'request' => $request->all(),
            'user' => Auth::user(),
            'ip' => $request->getClientIp()
        ]);

        $user = Auth::user();
        if (!$user->can('mark.cr')) {
            return response('没有权限', 403);
        }

        $request->validate([
            'analysis' => 'required',
            'start' => 'required',
        ]);

        if (is_null($request->text) && is_null($request->images)) {
            return response('文字和图片不可全为空', 400);
        }

        $critical = Critical::find($id);
        if ($critical->user_id != $user->id && !$user->hasRole('管理员')) {
            return response('没有权限', 403);
        }
        $critical->text = $request->text ?? '';
        $critical->images = is_null($request->images) ? '[]' : json_encode($request->images);
        $critical->analysis = $request->analysis;
        $critical->cost = (time() - $request->start / 1000);
        $critical->saveOrFail();

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
        $user = Auth::user();
        if (!$user->can('mark.cr')) {
            return response('没有权限', 403);
        }
        $critical = Critical::find($id);
        if ($critical->user_id != $user->id && !$user->hasRole('管理员')) {
            return response('非管理员只能删除自己的', 403);
        }
        Critical::destroy($id);
        return response('success');
    }
}

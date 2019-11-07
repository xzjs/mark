<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $users = User::all()->toArray();
            return response()->json($users);
        } else {
            return response('权限不足', 403);
        }
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
            'name' => 'required|max:255|unique:users',
        ]);
        $user = Auth::user();
        if ($user->isAdmin()) {
            $userModel = new User();
            $userModel->name = $request->name;
            $userModel->password = bcrypt('111111');
            $userModel->type = 1;
            $userModel->saveOrFail();
            return response($userModel->id);
        } else {
            return response("权限不足", 403);
        }
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
        $request->validate([
            'oldPwd' => 'required|max:255',
            'newPwd' => 'required|max:255',
        ]);
        $user = Auth::user();
        if (Hash::check($request->input('oldPwd'), $user->getAuthPassword())) {
            $user->password = bcrypt($request->input('newPwd'));
            $user->saveOrFail();
        } else {
            return response('原密码错误', 403);
        }
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

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
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->can('user')) {
            if($request->type){
                $users = User::permission($request->type)->get();
            }else{
                $users = User::all();
            }
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $user->getRoleNames(),
                ];
            }
            return response()->json($data);
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
            'roles' => 'required|array',
        ]);
        $user = Auth::user();
        if ($user->can('user')) {
            $userModel = new User();
            $userModel->name = $request->name;
            $userModel->password = bcrypt('111111');
            $userModel->saveOrFail();
            foreach ($request->roles as $role) {
                $userModel->assignRole($role);
            }
            return response('success');
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

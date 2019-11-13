<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'roles' => $user->getRoleNames(),
            ];
            return response()->json($data);
        } else {
            return response()->json(['message' => '未登录'], 401);
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
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|max:255',
        ]);
        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->put('id', $user->id);
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ];
            return response()->json($data);
        } else {
            return response()->json(['message' => '用户名或密码错误'], 401);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\UserService;

/**
 * Class UsersController.
 */
class UsersController extends Controller
{
    /**
     * @var UserService
     */
    public $userService;

    /**
     * UsersController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'name' => 'required|regex:/^[\pL\s\-]+$/u|min:3',
            'phone_number' => "sometimes|unique:users|regex:/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/u",
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        return $this->userService->store($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->userService->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->userService->find($id);
        $validator = Validator::make($request->all(), [
            'email' => "sometimes|email|unique:users,id,{$user->id}",
            'password' => 'sometimes|min:8',
            'name' => 'sometimes|regex:/^[\pL\s\-]+$/u|min:3',
            'phone_number' => "sometimes|unique:users,id,{$user->id}|regex:/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/u",
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        return $this->userService->update($user, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->userService->destroy($id)) {
            return response()->json([
                'message' => 'User deleted',
            ], 200);
        }

        // TODO: return this message in handler whatever error it is in production
        return response()->json([
            'message' => 'Server error',
        ], 500);
    }
}

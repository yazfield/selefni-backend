<?php

namespace App\Http\Controllers;

use App\Exceptions\ActivationCodeExpiredException;
use App\Exceptions\EmailAlreadyExistsException;
use App\Exceptions\PhoneNumberAlreadyExistsException;
use App\Services\Contracts\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * @var Validator
     */
    public $validator;

    /**
     * UsersController constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->validator = app('validator');
    }

    /**
     * Store a newly created resource in storage.
     * This could be either a user subscription
     * or a user created to hold items relation.
     */
    public function store(Request $request) : JsonResponse
    {
        $validator = $this->validator->make($request->all(), [
            'email'        => 'required|email',
            'password'     => 'required|min:8',
            'name'         => 'required|regex:/^[\pL\s\-]+$/u|min:3',
            'phone_number' => "sometimes|regex:/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/u",
            'avatar'       => 'sometimes|image',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 400);
        }
        $data = $request->all();
        $data[ 'avatar' ] = $request->file('avatar')->store('avatars');
        try {
            $user = $this->userService->store($data);
        } catch (EmailAlreadyExistsException $e) {
            \Storage::delete($data[ 'avatar' ]);
            $errors = [
                'email' => 'The email elready exists',
            ];

            return response()->json(['errors' => $errors], 400);
        } catch (PhoneNumberAlreadyExistsException $e) {
            \Storage::delete($data[ 'avatar' ]);
            $errors = [
                'phone_number' => 'The phone number elready exists',
            ];

            return response()->json(['errors' => $errors], 400);
        }

        return response()->json($user->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show($id) : JsonResponse
    {
        $user = $this->userService->find($id);

        return response()->json($user->toArray());
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json($user->toArray());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Use logged out'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     */
    public function update(Request $request, $id) : JsonResponse
    {
        $user = $this->userService->find($id);
        $validator = $this->validator->make($request->all(), [
            'email' => "sometimes|email|unique:users,id,{$user->id}",
            'password' => 'sometimes|min:8',
            'name' => 'sometimes|regex:/^[\pL\s\-]+$/u|min:3',
            'phone_number' => "sometimes|unique:users,id,{$user->id}|regex:/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{7})$/u",
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        $user = $this->userService->update($user, $request->all());

        return response()->json($user->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     */
    public function destroy($id) : JsonResponse
    {
        if ($this->userService->destroy($id)) {
            return response()->json([
                'message' => 'User deleted',
            ], 200);
        }

        // TODO: return this message in handler whatever error it is in production
        // make it an exception
        return response()->json([
            'error' => 'Server error',
        ], 500);
    }

    // TODO: this should be a html page not json
    public function activate($id, $code) : JsonResponse
    {
        $code = intval($code);
        try {
            $this->userService->activate($id, $code);

            return response()->json([
                'message' => 'User activated',
            ], 200);
        } catch (ActivationCodeExpiredException $e) {
            return response()->json([
                'error' => 'Activation code has expired',
            ], 300);
        }
    }
}

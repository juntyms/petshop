<?php

namespace App\Http\Controllers\Api\v1;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\HttpResponses;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Requests\Api\V1\UserLoginRequest;
use App\Interfaces\Api\V1\UserRepositoryInterface;

class UsersController extends Controller
{
    use HttpResponses;
    use HasJwtTokens;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show()
    {
        $userId = Auth::user()->id;

        //return $this->userRepository->getUserDetails($userId);

        // return response()->json([
        //     'data' => $this->userRepository->getUserDetails($userId)
        // ]);

        return UserResource::collection($this->userRepository->getUserDetails($userId));
    }


    public function destroy()
    {

    }


    public function orders()
    {

    }

    public function update(Request $request)
    {

    }

    public function login(UserLoginRequest $request)
    {

        $request->validated($request->all());

        if (Auth::attempt($request->only(['email','password']))) {

            $this->createToken(Auth::user()->uuid);

            $user = User::where('id', Auth::user()->id)->get();

            return UserResource::collection($user);

        } else {

            return $this->error('', 'Credentials do not match', 401);

        }

    }
}

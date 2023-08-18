<?php

namespace App\Http\Controllers\Api\v1;

use Auth;
use App\Models\User;
use App\Models\JwtToken;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\HttpResponses;
use App\Http\Resources\Api\V1\UserResource;

use App\Http\Requests\Api\V1\UserLoginRequest;
use App\Http\Requests\Api\V1\UserStoreRequest;
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

        return new UserResource($this->userRepository->getUserDetails($userId));
    }
    public function destroy()
    {
        $userId = Auth::user()->id;

        $this->userRepository->deleteUser($userId);

        return $this->success('', 'User Deleted', 200);

    }

    public function orders()
    {
        $userId = Auth::user()->id;

        return UserResource::collection($this->userRepository->getAllUserOrders($userId));

    }
    public function store(UserStoreRequest $request)
    {
        $request->validated($request->all());

        return new UserResource($this->userRepository->createUser($request->all()));
    }

    public function update(Request $request)
    {
        $userId = Auth::user()->id;

        return new UserResource($this->userRepository->updateUserDetails($userId, $request->all()));

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

    public function logout()
    {
        // Delete token
        JwtToken::where('user_id', Auth::user()->id)->delete();

        return $this->success([
            'message' => 'You have been logout'
        ]);

    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        //Find Email
        $user = User::where('email', $request->email)->firstOrFail();

        //Generate Token
        if ($user) {
            JwtToken::where('user_id', $user->id)->delete();

            return $this->success([

                'token' => $this->createToken($user->uuid)
            ], 'Token Generated', 200);

        }

        return $this->error('', 'Email Not Found', '403');
    }


}

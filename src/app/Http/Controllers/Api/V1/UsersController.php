<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Http\Requests\Api\V1\UserLoginRequest;
use App\Http\Requests\Api\V1\UserStoreRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\HttpResponses;
use App\Interfaces\Api\V1\UserRepositoryInterface;
use App\Models\JwtToken;
use App\Models\PasswordResetToken;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="User", description="User API Endpoint")
 */
class UsersController extends Controller
{
    use HttpResponses;
    use HasJwtTokens;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user",
     *      summary="View a User Account",
     *      tags={"User"},
     *      operationId="show",
     *      security={ {"bearerToken": {}} },
     * @OA\Response(response=200, description="OK"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Unprocessable Entity"),
     * @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function show()
    {
        $userId = Auth::user()->id;

        return new UserResource($this->userRepository->getUserDetails($userId));
    }

    /**
     * @OA\Delete(
     * path="/api/v1/user",
     * summary="Delete a User Account",
     * tags={"User"},
     * operationId="destroy",
     * security={ {"bearerToken": {}} },
     * @OA\Response(response=200, description="OK"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Unprocessable Entity"),
     * @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function destroy()
    {
        $userId = Auth::user()->id;

        $this->userRepository->deleteUser($userId);

        return $this->success('', 'User Deleted', 200);
    }

    /**
     * @OA\GET(
     *     path="/api/v1/user/orders",
     *     tags={"User"},
     *     summary="List all orders for the user",
     *     operationId="order",
     *     security={ {"bearerToken": {}} },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * @OA\Response(response=200, description="OK"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Unprocessable Entity"),
     * @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function orders(Request $request)
    {
        $userId = Auth::user()->id;

        $orders = $this->userRepository->getAllUserOrders($userId, $request->all());

        return OrderResource::collection($orders);
    }
    /**
     * @OA\Post(
     *   path="/api/v1/user/create",
     *   summary="Create new user",
     *   tags={"User"},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"first_name","last_name","email","password","avatar","address","phone_number"},
     *         @OA\Property(
     *           property="first_name",
     *           type="string",
     *           example="John",
     *           description="First Name"
     *         ),
     *         @OA\Property(
     *           property="last_name",
     *           type="string",
     *           example="Doe",
     *           description="last Name"
     *         ),
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           example="Doe",
     *           description="email"
     *         ),
     *         @OA\Property(
     *           property="password",
     *           type="password",
     *           description="password"
     *         ),
     *         @OA\Property(
     *           property="avatar",
     *           type="string",
     *           description="avatar"
     *         ),
     *         @OA\Property(
     *           property="address",
     *           type="string",
     *           description="Address"
     *         ),
     *         @OA\Property(
     *           property="phone_number",
     *           type="string",
     *           description="User Phone Number"
     *         ),
     *       ),
     *     ),
     *   ),
     *
     * @OA\Response(response=200, description="OK"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Unprocessable Entity"),
     * @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function store(UserStoreRequest $request)
    {
        $request['is_admin'] = 0;

        $request->validated($request->all());

        return new UserResource($this->userRepository->createUser($request->all()));
    }

    /**
     * @OA\Put(
     * path="/api/v1/user/edit",
     * summary="Update User record.",
     * tags={"User"},
     * security={ {"bearerToken": {}} },
     * @OA\RequestBody(
     *   required=true,
     *    @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *        @OA\Schema(
     *          required={"first_name","last_name","email","password","address","phone_number"},
     *            @OA\Property(
     *                property="first_name",
     *                type="string",
     *                description="User Firstname to be updated",
     *            ),
     *            @OA\Property(
     *                property="last_name",
     *                type="string",
     *                description="User Lastname to be updated",
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                description="User Email Address to be updated",
     *            ),
     *            @OA\Property(
     *                property="password",
     *                type="string",
     *                description="User password to be updated",
     *            ),
     *           @OA\Property(
     *                property="avatar",
     *                type="string",
     *                description="User Avatar UUID to be updated",
     *            ),
     *            @OA\Property(
     *                property="address",
     *                type="string",
     *                description="User Address to be updated",
     *            ),
     *            @OA\Property(
     *                property="phone_number",
     *                type="string",
     *                description="Users Phone number to be updated",
     *            ),
     *        ),
     *    ),
     * ),
     * @OA\Response(response=200, description="OK"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Unprocessable Entity"),
     * @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function update(Request $request)
    {
        $userId = Auth::user()->id;

        return new UserResource($this->userRepository->updateUserDetails($userId, $request->all()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     tags={"User"},
     *     summary="Logs user into system",
     *     operationId="User-login",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="The user name for login",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     * @OA\Response(response=200, description="OK"),
     * @OA\Response(response=401, description="Unauthorized"),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Unprocessable Entity"),
     * @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function login(UserLoginRequest $request)
    {
        $request->validated($request->all());

        if (Auth::attempt($request->only(['email','password']))) {
            $this->createToken(Auth::user()->uuid);

            $user = User::where('id', Auth::user()->id)->get();

            return UserResource::collection($user);
        }

        return $this->error('', 'Credentials do not match', 401);
    }

    /**
     * @OA\Get(
     *     tags={"User"},
     *     path="/api/v1/user/logout",
     *     summary="Logs out current logged in user session",
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=422, description="Unprocessable Entity"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function logout()
    {
        // Delete token
        JwtToken::where('user_id', Auth::user()->id)->delete();

        return $this->success([
            'message' => 'You have been logout'
        ]);
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/api/v1/user/forgot-password",
     *   summary="Create a token to reset a user password",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *          required={"email"},
     *           @OA\Property(
     *                property="email",
     *                type="string",
     *                description="User email",
     *            ),
     *        ),
     *     ),
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found"),
     *   @OA\Response(response=422, description="Unprocessable Entity"),
     *   @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        //Find Email
        $user = User::where('email', $request->email)->first();

        //Generate Token
        if ($user) {
            PasswordResetToken::where('email', $user->email)->delete();

            $new_token = uniqid();

            \DB::table('password_reset_tokens')
                ->insert([
                    'email' => $user->email,
                    'token' => $new_token,
                    'created_at' => Carbon::now()
                ]);

            return $this->success([
                'token' => $new_token
            ], 'Token Generated', 200);
        }

        return $this->error('', 'Email Not Found', '404');
    }

    /**
     * @OA\Post(
     *   tags={"User"},
     *   path="/api/v1/user/reset-password-token",
     *   summary="Rest a user password with a token",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *          required={"token","email","password","password_confirmation"},
     *           @OA\Property(
     *                property="token",
     *                type="string",
     *                description="User reset token",
     *            ),
     *            @OA\Property(
     *                property="email",
     *                type="string",
     *                description="User email",
     *            ),
     *            @OA\Property(
     *                property="password",
     *                type="string",
     *                description="User password",
     *            ),
     *            @OA\Property(
     *                property="password_confirmation",
     *                type="string",
     *                description="User password",
     *            ),
     *        ),
     *     ),
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found"),
     *   @OA\Response(response=422, description="Unprocessable Entity"),
     *   @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function resetPasswordToken(ResetPasswordRequest $request)
    {
        $request->validated($request->all());
        //find Email
        $resetToken = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if ($resetToken) {
            $user = User::where('email', $request->email)->first();

            $user->update(['password' => \Hash::make($request->password)]);

            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return $this->success('', 'Success Password Updated', 200);
        }

        return $this->error('', 'No Token Found for this email', 404);
    }
}

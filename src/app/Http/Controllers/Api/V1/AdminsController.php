<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AdminLoginRequest;
use App\Http\Requests\Api\V1\UserStoreRequest;
use App\Http\Resources\Api\V1\AdminResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\HttpResponses;
use App\Interfaces\Api\V1\UserRepositoryInterface;
use App\Models\JwtToken;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Admin", description="Admin Endpoint")
 */
class AdminsController extends Controller
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
     *    tags={"Admin"},
     *    path="/api/v1/admin/user-listing",
     *    summary="Get all list of posts",
     *    security={ {"bearerToken": {}} },
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(
     *      name="sortBy",
     *      in="query",
     *      @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *      name="desc",
     *      in="query",
     *      @OA\Schema(
     *        type="boolean",
     *        enum={"true","false"},
     *      )
     *    ),
     *    @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *      name="email",
     *      in="query",
     *      @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *      name="address",
     *      in="query",
     *      @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *      name="created_at",
     *      in="query",
     *      @OA\Schema(type="string")
     *    ),
     *    @OA\Parameter(
     *      name="marketing",
     *      in="query",
     *      @OA\Schema(
     *        type="string",
     *        enum={"1","0"},
     *      )
     *    ),
     *    @OA\Response(response=200, description="OK"),
     *    @OA\Response(response=401, description="Unauthorized"),
     *    @OA\Response(response=404, description="Not Found"),
     *    @OA\Response(response=422, description="Unprocessable Entity"),
     *    @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->getAllUserByLevel(0, $request->all());

        return AdminResource::collection($users);
    }

    /**
     * @OA\Post(
     *   tags={"Admin"},
     *   path="/api/v1/admin/create",
     *   summary="Create new user",
     *   security={ {"bearerToken": {}} },
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
     *    @OA\Response(response=200, description="OK"),
     *    @OA\Response(response=401, description="Unauthorized"),
     *    @OA\Response(response=404, description="Not Found"),
     *    @OA\Response(response=422, description="Unprocessable Entity"),
     *    @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function store(UserStoreRequest $request)
    {
        $request['is_admin'] = 1;

        $request->validated($request->all());

        return new UserResource($this->userRepository->createUser($request->all()));
    }

    /**
     * @OA\Post(
     *     tags={"Admin"},
     *     path="/api/v1/admin/login",
     *     summary="Logs user into system",
     *     operationId="login",
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
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=422, description="Unprocessable Entity"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function login(AdminLoginRequest $request)
    {
        $request->validated($request->all());

        if (Auth::attempt($request->only(['email','password']))) {

            $this->createToken(Auth::user()->uuid);

            $user = User::where('id', Auth::user()->id)->get();

            return AdminResource::collection($user);

        }

        return $this->error('', 'Credentials do not match', 401);
    }

    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/api/v1/admin/logout",
     *     summary="Logs out current logged in user session",
     *     operationId="logout",
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
     * @OA\Put(
     *    tags={"Admin"},
     *    path="/api/v1/admin/user-edit/{uuid}",
     *    summary="Update User record.",
     *    security={ {"bearerToken": {}} },
     *    @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="uuid of the User",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *             required={"first_name","last_name","email","password","address","phone_number"},
     *             @OA\Property(
     *                property="first_name",
     *                type="string",
     *                description="User Firstname to be updated",
     *             ),
     *             @OA\Property(
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
     *    @OA\Response(response=200, description="OK"),
     *    @OA\Response(response=401, description="Unauthorized"),
     *    @OA\Response(response=404, description="Not Found"),
     *    @OA\Response(response=422, description="Unprocessable Entity"),
     *    @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function update(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        if (empty($user)) {
            return $this->error('', 'Record Not Found', 404);
        }

        if ($user->is_admin === 1 && $user->uuid !== Auth::user()->uuid) {
            return $this->error('', 'You cannot update other admin user', 403);
        }

        $userId = $user->id;

        return new AdminResource($this->userRepository->updateUserDetails($userId, $request->all()));
    }

    /**
     * @OA\Delete(
     *   path="/api/v1/admin/user-delete/{uuid}",
     *   summary="Method to delete User from database.",
     *   tags={"Admin"},
     *   security={ {"bearerToken": {}} },
     *   @OA\Parameter(
     *       name="uuid",
     *       in="path",
     *       description="uuid of the User you want to delete",
     *       required=true,
     *       @OA\Schema(
     *          type="string"
     *       ),
     *  ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found"),
     *   @OA\Response(response=422, description="Unprocessable Entity"),
     *   @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)
            ->where('is_admin', 0)
            ->firstOrFail();

        $userId = $user->id;

        $this->userRepository->deleteUser($userId);

        return $this->success('', 'User Deleted', 200);
    }
}
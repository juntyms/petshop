<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Models\User;
use App\Models\JwtToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AdminResource;
use App\Http\Traits\Api\V1\HasJwtTokens;
use App\Http\Traits\Api\V1\HttpResponses;
use App\Http\Requests\Api\V1\AdminLoginRequest;
use App\Http\Requests\Api\V1\AdminStoreRequest;

/**
 * @OA\Tag(name="Admin", description="Admin Endpoint")
 */
class AdminsController extends Controller
{
    use HttpResponses, HasJwtTokens;
/**
     * @OA\Get(
     *      path="/api/v1/admin/user-listing",
     *      summary="Get all list of posts",
     *      tags={"Admin"},
     * @OA\Response(response="200", description="Success"),
     * @OA\Response(response="404", description="Not found"),
     * security={ {"bearerToken": {}} }
     * )
     */
    public function index()
    {
        $admins = User::where('is_admin',0)->get();

        return AdminResource::collection($admins);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/admin/create",
     *   summary="Create new user",
     *   tags={"Admin"},
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
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=404, description="Not Found"),
     *   security={ {"bearerToken": {}} }
     * )
     */
    public function store(AdminStoreRequest $request)
    {

        $request['is_admin'] = 1;

        $request->validated($request->all());

        $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'is_admin' => $request->is_admin,
                    'email' => $request->email,
                    'password' => $request->password,
                    'avatar' => $request->avatar,
                    'address' => $request->address,
                    'phone_number' => $request->phone_number,
                ]);


        return $this->success([
            'user' => $user
        ]);

    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/login",
     *     tags={"Admin"},
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
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Header(
     *             header="X-Rate-Limit",
     *             description="calls per hour allowed by the user",
     *             @OA\Schema(
     *                 type="integer",
     *                 format="int32"
     *             )
     *         ),
     *         @OA\Header(
     *             header="X-Expires-After",
     *             description="date in UTC when token expires",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="string"
     *         ),
     *         @OA\MediaType(
     *             mediaType="application/xml",
     *             @OA\Schema(
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid username/password supplied"
     *     )
     * )
     */
    public function login(AdminLoginRequest $request)
    {

        $request->validated($request->all());

        if (Auth::attempt($request->only(['email','password']))) {

                $this->createToken(Auth::user()->uuid);

                $user = User::where('id', Auth::user()->id)->get();

                return AdminResource::collection($user);

        } else {

             return $this->error('','Credentials do not match',401);

        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/logout",
     *     tags={"Admin"},
     *     summary="Logs out current logged in user session",
     *     operationId="logout",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function logout()
    {
        // Delete token
        JwtToken::where('user_id',Auth::user()->id)->delete();

        return $this->success([
            'message' => 'You have been logout'
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/v1/admin/user-edit/{uuid}",
     * summary="Update User record.",
     * tags={"Admin"},
     * security={ {"bearerToken": {}} },
     * @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="uuid of the User",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     * @OA\Response(response="200", description="Success"),
     * @OA\Response(response="404", description="Not found"),
     * )
     */
    public function update(Request $request, $uuid)
    {

        $user = User::where('uuid', $uuid)->first();

        if (empty($user)) {
            return $this->error('','Record Not Found',404);
        }

        if ($user->is_admin == 1 && $user->uuid != Auth::user()->uuid) {
            return $this->error('','You cannot update other admin user',403);
        }

        $user->update($request->all());

        return new AdminResource($user);
    }

     /**
     * @OA\Delete(
     * path="/api/v1/admin/user-delete/{uuid}",
     * summary="Method to delete User from database.",
     * tags={"Admin"},
     * security={ {"bearerToken": {}} },
     * @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="uuid of the User you want to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Response(response="200", description="Success"),
     * @OA\Response(response="404", description="User Not found"),
     * )
     */
    public function destroy($uuid)
    {
        $user = User::where('uuid',$uuid)->first();

        if (empty($user)) {
            return $this->error('','User Not Found',404);
        }

        if ($user->is_admin == 1) {
            return $this->error('','You Do not have permission to delete admin user',403);
        }

        $user->delete();

        return $this->success('','User Deleted',200);
    }

}
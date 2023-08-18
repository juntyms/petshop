<?php

namespace App\Repositories\Api\V1;

use App\Models\User;
use App\Interfaces\Api\V1\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(array $userDetails)
    {
        $user = User::create($userDetails);

        return $user;
    }
    public function getUserDetails($userId)
    {
        //return User::where('id', $userId)->get();
        $user = User::findOrFail($userId);

        return $user;
    }
    public function updateUserDetails($userId, array $newUserDetails)
    {
        $user = User::findOrFail($userId);

        $user->update($newUserDetails);

        return $user;

    }
    public function deleteUser($userId)
    {
        User::destroy($userId);
    }

    public function getAllUserOrders($userId)
    {
        $user = User::with('orders')->where('id', $userId)->get();

        return $user;
    }
}

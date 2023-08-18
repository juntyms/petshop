<?php

namespace App\Repositories\Api\V1;

use App\Models\User;
use App\Interfaces\Api\V1\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUserOrders($userId)
    {
        return User::with('Order')->where('id', $userId)->get();
    }
    public function getUserDetails($userId)
    {
        return User::where('id', $userId)->get();
    }
    public function updateUserDetails($userId, array $newUserDetails)
    {
        return User::where('id', $userId)->update($newUserDetails);
    }
    public function deleteUser($userId)
    {
        User::destroy($userId);
    }
}

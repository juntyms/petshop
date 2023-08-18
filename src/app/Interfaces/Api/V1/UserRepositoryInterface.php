<?php

namespace App\Interfaces\Api\V1;

interface UserRepositoryInterface
{
    public function createUser(array $userDetails);
    public function getUserDetails($userId);
    public function updateUserDetails($userId, array $newUserDetails);
    public function deleteUser($userId);
    public function getAllUserOrders($userId);
}

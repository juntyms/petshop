<?php

namespace App\Interfaces\Api\V1;

interface UserRepositoryInterface
{
    public function getAllUserOrders($userId);
    public function getUserDetails($userId);
    public function updateUserDetails($userId, array $newUserDetails);
    public function deleteUser($userId);
}

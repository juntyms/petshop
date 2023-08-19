<?php

namespace App\Repositories\Api\V1;

use App\Models\User;
use App\Models\Order;
use App\Interfaces\Api\V1\UserRepositoryInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

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

    public function getAllUserOrders($userId, array $filter)
    {
        $limit = array_key_exists('limit', $filter) ?? null;

        $page = array_key_exists('page', $filter) ?? null;

        $sortBy = array_key_exists('sortBy', $filter) ?? null;

        $order = Order::where('user_id', $userId)
                    ->when($limit, function (Builder $query) use ($filter) {
                        $query->limit($filter['limit']);
                    })->when($sortBy, function (Builder $query) use ($filter) {
                        $query->orderBy($filter['sortBy']);
                    })
                    ->paginate($page);

        return $order;
    }
}
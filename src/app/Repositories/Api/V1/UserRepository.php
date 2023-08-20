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
        return User::create($userDetails);
    }
    public function getUserDetails($userId)
    {
        return User::findOrFail($userId);
    }
    public function updateUserDetails($userId, array $newUserDetails)
    {
        $user = User::find($userId);

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

        return Order::where('user_id', $userId)
                    ->when($limit, function (Builder $query) use ($filter) {
                        $query->limit($filter['limit']);
                    })->when($sortBy, function (Builder $query) use ($filter) {
                        $query->orderBy($filter['sortBy']);
                    })
                    ->paginate($page);
    }

    public function getAllUserByLevel($userLevel, array $filter)
    {

        $limit = array_key_exists('limit', $filter) ?? null;

        $page = array_key_exists('page', $filter) ?? null;

        $sortBy = array_key_exists('sortBy', $filter) ?? null;

        $desc = array_key_exists('desc', $filter) ?? null;

        $first_name = array_key_exists('first_name', $filter) ?? null;

        $email = array_key_exists('email', $filter) ?? null;

        $phone_number = array_key_exists('phone_number', $filter) ?? null;

        $address = array_key_exists('address', $filter) ?? null;

        $created_at = array_key_exists('created_at', $filter) ?? null;

        $marketing = array_key_exists('marketing', $filter) ?? null;


        return User::where('is_admin', $userLevel)
                    ->when($limit, function (Builder $query) use ($filter) {
                        $query->limit($filter['limit']);
                    })
                    ->when($first_name, function (Builder $query) use ($filter) {
                        $query->where('first_name', $filter['first_name']);
                    })
                    ->when($email, function (Builder $query) use ($filter) {
                        $query->where('email', $filter['email']);
                    })
                    ->when($phone_number, function (Builder $query) use ($filter) {
                        $query->where('phone_number', $filter['phone_number']);
                    })
                    ->when($address, function (Builder $query) use ($filter) {
                        $query->where('address', $filter['address']);
                    })
                    ->when($created_at, function (Builder $query) use ($filter) {
                        $query->where('created_at', $filter['created_at']);
                    })
                    ->when($marketing, function (Builder $query) use ($filter) {
                        $query->where('is_marketing', $filter['marketing']);
                    })
                    ->when($sortBy, function (Builder $query) use ($filter) {
                        $query->orderBy($filter['sortBy']);
                    })
                    ->paginate($page);


    }
}

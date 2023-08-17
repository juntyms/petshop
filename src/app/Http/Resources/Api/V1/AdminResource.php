<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => (string)$this->id,
            'type' => 'Users',
            'attributes' => [
                    'uuid' => (string)$this->uuid,
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'address' => $this->address,
                    'phone_number' => $this->phone_number,
                    'is_admin' => $this->is_admin ? true : false,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at
            ],
            'relationships' => [
                'id' => $this->jwttoken->id ?? null,
                'token' => $this->jwttoken->token_title ?? null
            ]
        ];
    }
}
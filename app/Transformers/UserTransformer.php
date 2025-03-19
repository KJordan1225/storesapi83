<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toIso8601String(),
            'updated_at' => $user->updated_at->toIso8601String(),
            // 'profile' => [
            //     [
            //         'id' => (int) $user->userProfile->id,
            //         'first_name' => $user->userProfile->first_name,
            //         'last_name' => $user->userProfile->last_name,
            //         'address1' => $user->userProfile->address1,
            //         'address2' => $user->userProfile->address2,
            //         'city' => $user->userProfile->city,
            //         'state' => $user->userProfile->state,
            //         'zip_code' => $user->userProfile->zip_code,
            //         'phone_number' => $user->userProfile->phone_number,
            //         'phone_type' => $user->userProfile->phone_type,
            //         'dob' => $user->userProfile->dob,
            //         'queversary' => $user->userProfile->queversary,
            //     ],
            // ],
        ];
    
    }
}

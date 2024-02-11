<?php

namespace App\Policies;

use App\Models\MyPlant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MyPlantPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MyPlant $myPlant): Response
    {
        return $user->id === $myPlant->user_id
            ? Response::allow()
            : Response::deny('You do not own this plant.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MyPlant $myPlant): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MyPlant $myPlant): bool
    {
        //
    }
}

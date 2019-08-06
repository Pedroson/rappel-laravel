<?php

namespace App\Repositories;

use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        // TODO: Implement all() method.
    }

    public function get(User $user)
    {
        // TODO: Implement get() method.
    }

    public function update(UpdateUserFormRequest $request)
    {
        // TODO: Implement update() method.
    }

    public function updateProfilePicture(User $user, ProfilePictureRequest $request)
    {
        // TODO: Implement updateProfilePicture() method.
    }

    public function delete(User $user)
    {
        // TODO: Implement delete() method.
    }

}
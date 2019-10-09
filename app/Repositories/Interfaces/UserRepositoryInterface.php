<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\User;

interface UserRepositoryInterface
{
    public function all();

    public function get(User $user);

    public function update(User $user, UpdateUserFormRequest $request);

    public function updateProfilePicture(User $user, ProfilePictureRequest $request);

    public function delete(User $user);
}

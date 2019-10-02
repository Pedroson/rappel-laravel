<?php

namespace App\Repositories;

use App\Exceptions\SystemException;
use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MongoDB\Driver\Exception\LogicException;

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
        if($user)
        {
            $fileName = str_random().'.'.$request->profile_picture->getClientOriginalExtension();
            if($file = $request->profile_picture->storeAs('profile_pictures/'.$user->id, $fileName))
            {
                $user->profile_picture = $fileName;

                if($user->save())
                {
                    return response([
                        'status' => 'success',
                        'msg' => 'profile picture updated',
                        'data' => $file
                    ], 200);
                }
                else
                {
                    throw new SystemException('unable to save profile picture', 500);
                }
            }
            else
            {
                throw new SystemException('unable to upload profile picture', 500);
            }
        }

        throw new SystemException('unable to upload profile picture', 400);
    }

    public function delete(User $user)
    {
        // TODO: Implement delete() method.
    }

}

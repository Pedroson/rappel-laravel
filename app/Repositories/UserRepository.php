<?php

namespace App\Repositories;

use App\Exceptions\SystemException;
use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

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

    /**
     * Updates existing user model in the database.
     *
     * @param User $user
     * @param UpdateUserFormRequest $request
     * @return ResponseFactory|Response
     * @throws SystemException
     */
    public function update(User $user, UpdateUserFormRequest $request)
    {
        try
        {
            if($user)
            {
                $user->name = $request->name;
                $user->email = $request->email;
                if($user->save())
                {
                    return response([
                        'status'    => 'success',
                        'msg'       => 'profile has been updated'
                    ], 200);
                }
                throw new SystemException('unable to update profile', 500);
            }
            throw new SystemException('user not recognised', 400);
        }
        catch(Exception $e)
        {
            throw new SystemException($e->getMessage(), 500);
        }
    }

    /**
     * Updates user model profile picture in database.
     * Stores uploaded image in storage
     *
     * @param User $user
     * @param ProfilePictureRequest $request
     * @return ResponseFactory|Response
     * @throws SystemException
     */
    public function updateProfilePicture(User $user, ProfilePictureRequest $request)
    {
        try
        {
            if ($user)
            {
                $fileName = str_random() . '.' . $request->profile_picture->getClientOriginalExtension();
                if ($file = $request->profile_picture->storeAs('profile_pictures/' . $user->id, $fileName))
                {
                    $user->profile_picture = $fileName;

                    if ($user->save())
                    {
                        return response([
                            'status' => 'success',
                            'msg' => 'profile picture updated',
                            'data' => $file
                        ], 200);
                    }
                    throw new SystemException('unable to save profile picture', 500);
                }
                throw new SystemException('unable to upload profile picture', 500);
            }
            throw new SystemException('account not found', 400);
        }
        catch(Exception $e)
        {
            throw new SystemException($e->getMessage(), 500);
        }
    }

    public function delete(User $user)
    {
        // TODO: Implement delete() method.
    }

}

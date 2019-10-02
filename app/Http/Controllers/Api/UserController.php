<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Repositories\UserRepository;
use App\User;
use Exception;

class UserController extends Controller
{
    protected $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function update(UpdateUserFormRequest $request, $id)
    {
        $user = User::find($id);

        if($user)
        {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return response([
                'status'    => 'success',
                'msg'       => 'profile has been updated'
            ], 200);
        }

        return response([
            'status'    => 'error',
            'msg'       => 'user not recognised'
        ], 400);
    }

    public function updateProfilePicture(ProfilePictureRequest $request, $id)
    {
        $user = User::find($id);
        $this->UserRepository->updateProfilePicture($user, $request);
        return response()->json([
            'status'    => 'success',
            'msg'       => 'profile picture updated'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\User;

class UserController extends Controller
{
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

        if($user)
        {
            $fileName = str_random().'.'.$request->profile_picture->getClientOriginalExtension();
            if($file = $request->profile_picture->storeAs('profile_pictures/'.$id, $fileName)) {
                $user->profile_picture = $fileName;
                $user->save();
                return response([
                    'status'    => 'success',
                    'msg'       => 'profile picture updated',
                    'data'      => $file
                ], 200);
            }
            else
            {
                return response([
                    'status'    => 'error',
                    'msg'       => 'problem saving image'
                ], 400);
            }
        }

        return response([
            'status'    => 'error',
            'msg'       => 'user not recognised'
        ], 400);
    }
}

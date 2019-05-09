<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFormRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(UpdateFormRequest $request, $id)
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
}

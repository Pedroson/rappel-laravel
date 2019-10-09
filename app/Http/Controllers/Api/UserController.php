<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\SystemException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilePictureRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * Updates user record in database
     *
     * @param UpdateUserFormRequest $request
     * @param $id
     * @return ResponseFactory|Response
     * @throws SystemException
     */
    public function update(UpdateUserFormRequest $request, $id)
    {
        $user = User::find($id);
        return $this->UserRepository->update($user, $request);

    }

    /**
     * Updates users profile picture in database and file storage
     *
     * @param ProfilePictureRequest $request
     * @param $id
     * @return ResponseFactory|Response
     * @throws SystemException
     */
    public function updateProfilePicture(ProfilePictureRequest $request, $id)
    {
        $user = User::find($id);
        return $this->UserRepository->updateProfilePicture($user, $request);
    }
}

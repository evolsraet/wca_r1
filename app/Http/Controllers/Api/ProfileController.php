<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function update(UpdateProfileRequest $request)
    {
        $profile = Auth::user();
        $profile->name = $request->name;
        $profile->email = $request->email;

        if ($profile->save()) {
            return $this->successResponse($profile, 'User updated');;
            // return $this->successResponse($profile, 'User updated');;
        }
        return response()->json(['message' => 'User not updated'], 403);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return new UserResource($user);

        // return response()->json(['data' => $user]);
        // return $this->successResponse([$user], 'User found');
        return $this->successResponse([$user], 'User found');
    }
}

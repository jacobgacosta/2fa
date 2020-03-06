<?php

namespace App\Http\Controllers;

use App\Models\User;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthyTokenController extends Controller
{
    /**
     * Validate the Authy token.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateToken(Request $request)
    {
        $this->validate($request, [
            'email' => 'email',
            'token' => 'required',
        ]);

        $authyApi = new AuthyApi(env('API_KEY'));

        $user = User::where('email', $request->email)->first();

        $response = $authyApi->verifyToken($user->authyProfile->authy_id, $request->token);

        if (!$response->ok()) {
            throw ValidationException::withMessages(['error' => $response->message()]);
        }

        return response()->json([
            'status' => 'Valid Token :D',
        ]);
    }
}

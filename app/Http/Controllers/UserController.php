<?php

namespace App\Http\Controllers;

use App\Models\User;
use Authy\AuthyApi;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the main view.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code' => 'required|numeric',
            'phone' => 'numeric',
            'email' => 'email',
        ]);

        $authyApi = new AuthyApi(env('API_KEY'));

        $authyUser = $authyApi->registerUser($request->email, $request->phone, $request->code);

        if (!$authyUser->ok()) {
            return response()->json(['status' => 'error']);
        }

        $user = User::forceCreate([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user->authyProfile()->create([
            'authy_id' => $authyUser->id()
        ]);

        /*$render = new ImageRenderer(new RendererStyle(400), new SvgImageBackEnd());

        $writer = new Writer($render);

        $secret = env('APP_SECRET');

        $em = $request->email;

        $content = "otpauth://totp/demo:$em?secret=$secret";

        $file = $writer->writeString($content);*/

        return response()->json(['status' => 'success']);
    }
}

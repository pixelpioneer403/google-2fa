<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Support\Facades\Validator;


class SettingController extends Controller
{
    public function index() {
        return view('settings');
    }

    public function toggle2fa(Request $request) {

        $validator = Validator::make($request->all(), [
            'is_enabled2fa' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }else{

            if ($request->is_enabled2fa == 'true') {
                $user = auth()->user();

                // Generate a secret key
                $google2fa = app(Google2FA::class);
                $secretKey = $google2fa->generateSecretKey();

                // Store the secret key for the user
                $user->google2fa_secret = $secretKey;
                $user->save();

                // Generate QR code URL for the secret key
                $qrCodeUrl = $google2fa->getQRCodeInline(
                    config('app.name'),
                    $user->email,
                    $secretKey
                );

                // Return the secret key and QR code URL to the user
                return response()->json([
                    'secret_key' => $secretKey,
                    'qrcode' => true,
                    'qr_code_url' => $qrCodeUrl,
                ]);
            }
            else{
                $user = auth()->user();
                if ($user->google2fa_secret) {
                    $user->google2fa_secret = null;
                    $user->save();

                    // This Will Show Null Because We are using Attribute Casting Value is Null But it is showing in database because it also encrypt null
                    // dd($user->google2fa_secret);
                    return response()->json(['status' => true,'qrcode' => false, 'message' => '2FA disabled successfully']);
                }else{
                    return response()->json(['status' => false, 'message' => '2FA is not enabled for the user']);
                }
            }
        }
    }

    public function verify2fa(Request $request) {
        $request->validate([
            'google2fa_secret' => 'required',
        ]);

        $user = auth()->user();
        $google2fa = app(Google2FA::class);
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->google2fa_secret);

        if ($valid) {
            return redirect(route('dashboard'));
        }else{
            dd('Code Invalid');
        }
    }
}

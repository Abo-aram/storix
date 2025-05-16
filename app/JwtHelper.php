<?php

namespace App;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;


trait JwtHelper
{
    protected $secret = "hello";

    public function base64Encode($data){
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64Decode($data){
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function generateJwt($payload,$exp=15){
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $payload['iat'] = time();
        $payload['exp'] = time() + ($exp * 60);
      
        $base64Header = $this->base64Encode(json_encode($header));
        $base64Payload = $this->base64Encode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload",$this->secret,true);
        $base64Signature = $this->base64Encode($signature);

        return "$base64Header.$base64Payload.$base64Signature";

    }

    public function validateJwt($token){
        $parts = explode('.', $token);


        if(count($parts) != 3){
            return false;

        }

        [$header, $payload, $signature] = $parts;

        if (!$token){
            return false;
        }

        $expectedSignature = $this->base64Encode(
            hash_hmac('sha256', "$header.$payload", $this->secret, true)
        );

        if (!hash_equals($expectedSignature, $signature)){
            return false;
        
        }

        $decodedPayload = json_decode($this->base64Decode($payload), true);

       

        return $decodedPayload;
    }


     public function AuthUser($accessToken) {
    // 1. Validate access token
    $accessPayload = $this->validateJwt($accessToken);

    if ($accessPayload && $accessPayload['exp'] > time()) {
        return true; // All good
    }

    // 2. Try to refresh using refresh token
    if (!$accessPayload || $accessPayload['exp'] < time()) {
        if (!isset($accessPayload['id'])) return false;

        $user = User::find($accessPayload['id']);
        if (!$user || !$user->refreshToken) return false;

        $refreshPayload = $this->validateJwt($user->refreshToken);
        if (!$refreshPayload || $refreshPayload['exp'] < time()) {
            return false; // Refresh token also expired
        }

        // 3. Generate new access token
        $newAccessToken = $this->generateJwt([
            'id' => $user->id,
            'type' => 'access',
        ]);

        // Normally you’d want to return this cookie to the controller or middleware
        $cookie = cookie(
        'access_token',           // name
        $newAccessToken,             // value
                       // sameSite
        );
        return true;
    }

    return false;
}

    

    


    

}

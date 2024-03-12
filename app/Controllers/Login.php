<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Apiuser;
use \Firebase\JWT\JWT;

class Login extends BaseController {
    use ResponseTrait;

    public function postRegister() {
        $apiusersModel = new Apiuser();

        $email = $this->request -> getVar('email');
        $password = $this->request -> getVar('password');

        $user = $apiusersModel -> where('email', $email)->first();

        if(is_null($user)) {
            return $this->fail('Identifiants invalides', 401);
        } if($password != $user -> password) {
            return $this->fail('Identifiants invalides', 401);
        }

        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = $iat + 36000000;
        $payload = array(
            "sub" => "API restbooks",
            "email" => $user->email,
            "iat" => $iat,
            "exp" => $exp
        );

        $token = JWT::encode($payload, $key, 'HS256');
        $response = [
            'message' => 'Connexion réussie',
            'token' => $token
        ];

        return $this -> respond($response);
    }
}
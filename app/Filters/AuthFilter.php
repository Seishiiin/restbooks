<?php

    namespace App\Filters;

    use CodeIgniter\Filters\FilterInterface;
    use CodeIgniter\HTTP\RequestInterface;
    use CodeIgniter\HTTP\ResponseInterface;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class AuthFilter implements FilterInterface {

        public function before(RequestInterface $request, $arguments = null) {
            $key = getenv('JWT_SECRET');
            $header = $request -> getHeader("Authorization");
            $token = null;

            if(!empty($header)) {
                if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                    $token = $matches[1];
                }
            }

            if(is_null($token) || empty($token)) {
                $response = service('response');
                $response -> setBody('Accès interdit');
                $response -> setStatusCode(401);
                return $response;
            }

            try {
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
            } catch (Exception $e) {
                $response = service('response');
                $response -> setBody('Accès interdit');
                $response -> setStatusCode(401);
                return $response;
            }
        }

        public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {

        }
    }

?>
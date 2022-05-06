<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Firebase\JWT\JWT;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $publicKey = <<<EOD
        -----BEGIN PUBLIC KEY-----
        MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv6z3o6eLuuSHMgoIp46C
        3byjzVz9X6bnjeKzbPWRbbffH6XCxY1iobKYP1KUxMkEXCnEtvt0eOhvwyNvaGd7
        H6WhI/jdvtmUgVdGrsHOJtuN8chBBvnDaT2DT3zGFNKAeHAjoeLm6QwWFP+uJB7U
        WXp06QgY+E1PlSvduGxBS2YCBIP4IYFlJe2X6EEIIf7XXkeIfxw3UJfMlIENzFWO
        p7IOcfyCmmYv0G6238LS8jLSpy6xSSsQmn6EeQQFO9A5LJ/CxD4mPU5kBaveV7Ly
        PxBChxZNnTQ/dRuHBFViPcpZlkCT+10jAqou4A8WaMPp70x+7br0eeKyN09vuhrW
        fQIDAQAB
        -----END PUBLIC KEY-----
        EOD;

        $response = Services::response();

        if (!$request->hasHeader('Authorization')) {
            $data = [
                'status' => 401,
                'error' => true,
                'message' => 'Akses Ditolak'
            ];
            $response->setStatusCode(401, 'Akses Ditolak');

            return $response->setJSON($data);
        }

        $token = $request->header("Authorization")->getValue();

        try {
            $decoded = JWT::decode($token, $publicKey, array('RS256'));

            if ($decoded) {
                return [true];
            }
        } catch (\Exception $e) {
            $data = [
                'status' => 401,
                'error' => true,
                'message' => 'Akses Ditolak Token Tidak Valid'
            ];
            
            $response->setStatusCode(401, 'Akses Ditolak');

            return $response->setJSON($data);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
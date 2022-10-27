<?php
/**
 * Authorize.php
 * @author huangbinbin
 * @date   2022/10/27 10:35
 */

namespace Crasp\Trustpilot\Api;

use Crasp\Trustpilot\Traits\HasHttpRequest;

class Authorize
{
    use HasHttpRequest;

    private $config;
    private $loginUrl = 'https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword';
    private $codeUrl = 'https://authenticate.trustpilot.com/business-login';
    private $tokenUrl = 'https://authenticate.b2b.trustpilot.com/v1/oauth/accesstoken';
    private $key;
    private $clientId;
    private $redirectUrl = 'https://businessapp.b2b.trustpilot.com/reviews/?locale=en-US';


    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->key = $config['key'];
        $this->clientId = $config['client_id'];
    }


    /**
     * @return mixed
     * @throws \Exception
     * @author huangbinbin
     * @date   2022/10/27 10:56
     */
    public function login(): mixed
    {
        $parmas = [
            'key' => $this->key,
        ];
        $body = [
            'returnSecureToken' => true,
            'email'             => $this->config['email'],
            'password'          => $this->config['password'],
        ];
        $url = $this->loginUrl . '?' . http_build_query($parmas);
        $response = $this->post($url, $body);
        if (!isset($response['idToken'])) {
            throw new \Exception('模拟登录失败');
        }

        $token = $response['idToken'];
        $code = $this->getCode($token);

        return $this->getAccessToken($code);
    }

    /**
     * @param $token
     *
     * @return mixed
     * @throws \Exception
     * @author huangbinbin
     * @date   2022/10/27 11:01
     */
    public function getCode($token)
    {
        $parmas = [
            'redirect_uri'  => $this->redirectUrl,
            'client_id'     => $this->clientId,
            'locale'        => 'en-US',
            'response_type' => 'code',
            'v'             => 2,
        ];
        $body = [
            'token'    => $token,
            'username' => $this->config['email'],
        ];
        $url = $this->codeUrl . '?' . http_build_query($parmas);
        $response = $this->post($url, $body);
        if (!isset($response['code'])) {
            throw new \Exception('模拟登录失败');
        }

        return $response['code'];
    }

    /**
     * @param $code
     *
     * @return mixed
     * @throws \Exception
     * @author huangbinbin
     * @date   2022/10/27 11:03
     */
    public function getAccessToken($code): mixed
    {
        $body = [
            "authorizationCode" => $code,
            "redirectUri"       => $this->redirectUrl,
        ];
        $response = $this->post($this->tokenUrl, $body, [
            'Content-Type' => 'application/json',
        ]);
        if (!isset($response['accessToken'])) {
            throw new \Exception('模拟登录失败');
        }

        return $response['accessToken'];
    }

}
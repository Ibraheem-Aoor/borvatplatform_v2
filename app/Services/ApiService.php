<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Throwable;

class ApiService
{


    protected $base_url;
    protected $headers;

    protected $auth_credits;

    protected $http_client;

    /**
     * @param $base_url represnts base url target.
     * @param $headers represnts headers  to sent with each request
     * @param $auth_credits represnts auth data  authorize .
     */
    public function __construct($base_url, array $headers, array $auth_credits = [])
    {
        $this->base_url = $base_url;
        $this->auth_credits = $auth_credits;
        $this->headers = $headers;
        $this->authenticate();
    }

    /**
     * Set Http Client to reuse in service
     */
    protected function authenticate()
    {
        $this->http_client = Http::baseUrl($this->base_url);
        if (isset($this->auth_credits['auth_type'])  && $this->auth_credits['auth_type'] == 'berar_token') {
            $this->http_client->withToken($this->auth_credits['token']);
        } elseif (isset($this->auth_credits['auth_type'])  && $this->auth_credits['auth_type'] == 'basic_auth') {
            $this->http_client->withBasicAuth($this->auth_credits['user_name'], $this->auth_credits['password']);
        }
        $this->http_client->withHeaders($this->headers);
    }


    /**
     * GEt Request
     * @param string $endpoint
     * @param array $params
     */
    public function get(string $endpoint, array $params = [])
    {
        $response = $this->http_client
            ->get($endpoint, $params);
        return $response;
    }


    /**
     * POST Request
     * @param string $endpoint
     * @param array $data represents the form data to send
     */
    public function post(string $endpoint, array $data)
    {
        $response = $this->http_client
            ->post($endpoint, $data);
        return $response;
    }


    /**
     * PUT Request
     * @param string $endpoint
     * @param array $data represents the form data to send
     */
    public function put(string $endpoint, array $data)
    {
        $response = $this->http_client
            ->timeout(180)
            ->put($endpoint, $data);
        return $response;
    }

    ##### START SETTER/GETTER #######
    public function setAuthCredits(array $auth_credits) : void
    {
        $this->auth_credits = $auth_credits;
    }

    public function getAuthCredits() : array
    {
        return $this->auth_credits;
    }

    public function setHeaders(array $headers) : void
    {
        $this->headers = $headers;
    }
    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function setBaseUrl(string $base_url) : void
    {
        $this->base_url = $base_url;
    }

    public function getBaseUrl() : string
    {
        return $this->base_url;
    }


    ##### END SETTER/GETTER #######

}

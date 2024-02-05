<?php
namespace App\Traits;
use App\Services\ApiService;

trait ShouldSetApiConnection
{

    protected $api_service,
    $auth_type,
    $base_url, $headers, $user_name, $password, $token;


    public function __invoke(
        string $auth_type = 'basic_auth',
        string $base_url = '',
        array $headers = [],
        string $user_name = '',
        string $password = '',
        string $token = '',
    ) {
        $this->auth_type = $auth_type;
        $this->base_url = $base_url;
        $this->headers = $headers;
        $this->user_name = $user_name;
        $this->password = $password;
        $this->token = $token;
        $this->setApiService();
    }


    /**
     * Instansiate Api service to communicate with  any api.
     */
    public function setApiService()
    {
        $this->api_service = new ApiService($this->base_url, $this->headers, [
            'auth_type' => $this->auth_type,
            'user_name' => $this->user_name,
            'password' => $this->password,
            'token' => $this->token,
        ]);
    }

}

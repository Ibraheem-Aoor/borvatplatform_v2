<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;
use Throwable;

class BolRetailerApiService
{
    private $client, $client_id, $client_key, $bol_account;
    public function __construct(BolAccount $bol_account)
    {
        $this->bol_account = $bol_account;
        $this->client_id = $this->bol_account->client_id;
        $this->client_key = $this->bol_account->client_key;
        $this->client = new Client();
        $this->client->authenticateByClientCredentials($this->client_id, $this->client_key);
        $this->generateToken();
    }


    /**
     * handle Client Auth and genereate/regenerate the token.
     */
    public function generateToken()
    {
        if (
            !Cache::has($this->bol_account->name . '_token') ||
            $this->client == null ||
            $this->client?->getAccessToken() ||
            $this->client?->getAccessToken()?->getToken() == null
        ) {
            $token_epiration = $this->client->getAccessToken()->getExpiresAt();
            Cache::remember($this->bol_account->name . '_token', $token_epiration, function () {
                $this->client->authenticateByClientCredentials($this->client_id, $this->client_key);
                return $this->client->getAccessToken()->getToken();
            });
        }
    }



    ######### START GETTER/SEETERS #########
    public function getClient(): Client
    {
        return $this->client;
    }

    public function getBolAccount(): BolAccount
    {
        return $this->bol_account;
    }
    ######### END GETTER/SEETERS #########

}
?>

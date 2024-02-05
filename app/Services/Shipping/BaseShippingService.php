<?php
namespace App\Services\Shipping;

use App\Contracts\ApiContract;
use App\Services\ApiService;
use App\Traits\ShouldSetApiConnection;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Catch_;
use Picqer\BolRetailerV10\Client;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;

class BaseShippingService implements ApiContract
{

    use ShouldSetApiConnection;
}

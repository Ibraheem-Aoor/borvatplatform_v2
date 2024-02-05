<?php
namespace App\Services\BOL;

use App\Models\BolAccount;
use App\Models\Order;
use App\Models\Product;
use App\Services\BOL\BolOrderProductsService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Picqer\BolRetailerV10\Exception\RateLimitException;
use Picqer\BolRetailerV10\Exception\UnauthorizedException;
use Picqer\BolRetailerV10\Model\CatalogProduct;
use Throwable;
use Picqer\BolRetailerV10\Model\Order as BolOrder;
use Picqer\BolRetailerV10\Model\ReducedOrder;

class BolProductService extends BaseBolService
{
    protected const MODEL = Product::class;

    /**
     * Products have M-M RelationShip with Orders.
     * @var $bol_order_products_service represnets that relationShip
     */
    protected $bol_order_products_service;

    public function __construct(BolAccount $bol_account)
    {
        parent::__construct($bol_account);
        $this->bol_order_products_service = new BolOrderProductsService($bol_account);

    }


    /**
     * Store all order items.
     */
    public function storeFromOrder($bol_order_data, Order $stored_order)
    {
        foreach ($bol_order_data->orderItems as $orderItem) {
            $bol_product_data = $orderItem;
            $ean = ($bol_order_data instanceof ReducedOrder) ? $orderItem->ean : $orderItem->product->ean;
            $product_catalog = $this->bol_retailer_service->getClient()->getCatalogProduct($ean);
            $product_name = $this->getProductNameFromCatalog($product_catalog);
            $product = [
                'title' => $product_name,
                'ean' => $ean,
            ];
            $stored_product = $this->storeProduct($product);
            $order_product = array_merge($bol_product_data->toArray(), ['order_id' => $stored_order->id, 'product_id' => $stored_product->id]);
            $this->bol_order_products_service->store($order_product);
        }
    }

    public function storeProduct(array $product): ?Product
    {
        try {
            DB::beginTransaction();
            $product = Product::create($product);
            DB::commit();
            return $product;
        } catch (QueryException $e) {
            $erro_code = $e->errorInfo[1];
            if ($erro_code == 1062) {
                DB::rollBack();
                $duplicated_product = Product::query()->where('ean', $product['ean'])->first();
                $duplicated_product->num_of_sales += 1;
                $duplicated_product->save();
                return $duplicated_product;
            }
        } catch (Throwable $e) {
            info('BOL PRODUCT SERVICE ERROR IN storeProduct: ');
            info($e->getMessage());
        }
    }

    /**
     * Extract The Product Name From The Product Catalog
     */
    public function getProductNameFromCatalog(CatalogProduct $product_catalog): string
    {
        foreach ($product_catalog->attributes as $attribute) {
            if ($attribute->id == 'Title') {
                return $attribute->values[0]->value;
            }
        }
    }


    /**
     * Fetch & Store Images For Products
     */
    public function fetchImages()
    {
        $this->getBolRetailer()
            ->getBolAccount()
            ->products()
            ->whereNull('image')
            ->chunk(50, function ($products) {
                $this->storeImages($products);
            });
    }

    private function storeImages(Collection $products)
    {
        foreach ($products as $product) {
            try {
                $product_image_url = $this->getProductImage($product->ean);
                $product_image_extension = pathinfo((string) $product_image_url, PATHINFO_EXTENSION);
                $product_bol_image = file_get_contents((string) $product_image_url);
                $product_image_name = time() . '.' . $product_image_extension;
                Storage::disk('public')->put("products/{$product->id}/{$product_image_name}", $product_bol_image);
                $product->image = $product_image_name;
                $product->save();
                info('Product Image Saved');
            } catch (Throwable $e) {
                info('Product Image Error: ');
                info($e->getMessage());
            }
        } // 50 products itreated
    }


    /**
     * Get Product  Image Url
     * @return \url
     */
    public function getProductImage($ean)
    {
        $product_assets = $this->getProducAssets($ean)[0];
        $image = @$product_assets->variants[0]->url ?? @$product_assets->variants[0]->url;
        return $image;
    }
    /**
     * get product assets.
     * @throws RateLimitException
     * @throws UnauthorizedException
     * @throws Throwable
     */
    public function getProducAssets($ean)
    {
        try {
            if (!Cache::has($this->getBolRetailer()->getBolAccount()->name . '_product_assets_rate_limit_reached')) {
                $this->getBolRetailer()->generateToken();
                return $this->getBolRetailer()->getClient()->getProductAssets($ean);
            }
        } catch (RateLimitException $e) {
            Cache::remember(
                $this->getBolRetailer()->getBolAccount()->name . '_product_assets_rate_limit_reached',
                Carbon::now()->addSeconds($e->getRetryAfter()),
                function () {
                    return true;
                }
            );
            sleep($e->getRetryAfter());
            return $this->getProducAssets($ean);
        } catch (UnauthorizedException $e) {
            $this->getBolRetailer()->generateToken();
            return $this->getProducAssets($ean);
        }
    }






}
?>

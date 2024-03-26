<?php
namespace App\DataTables\BolAccount;

use App\Models\BolAccount;
use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class BolAccountTransformer extends TransformerAbstract
{
    public function transform(BolAccount $bol_account)
    {
        return [
            'checkbox' => '<input type="checkbox" name="id[]" value="'.$bol_account->id.'" >',
            'logo' => $this->getImageElement($bol_account),
            'name' => $bol_account->name,
            'created_at' => date($bol_account->created_at),
            'action' => '<button id="btn-'.$bol_account->id.'" type="button" class="btn-sm btn btn-success"
                    data-id="'.$bol_account->id.'" data-name="'.$bol_account->name.'"
                    data-client-id="'.$bol_account->client_id.'" data-client-key="'.$bol_account->client_key.'"
                    data-address_street="'.@$bol_account->address['street'].'" data-address_city="'.@$bol_account->address['city'].'"
                    data-address_country="'.@$bol_account->address['country'].'" data-address_zipcode="'.@$bol_account->address['zipcode'].'"
                    data-action="'.route('bol_accounts.update' , $bol_account->id).'" data-method="POST"
                    '.$this->getDataImageAttr($bol_account).
                    'data-bs-toggle="modal" data-bs-target="#account-create-update-modal"><i
                        class="fa fa-edit"></i></button>',
        ];
    }

    public function getImageElement($bol_account)
    {
        return '<img id="'.$bol_account->id.'" src="'.getImageUrl($bol_account->logo).'" width="100"/>';
    }


    public function getDataImageAttr($bol_account)
    {
        return $bol_account->logo ? 'data-image="'.getImageUrl($bol_account->logo).'"'  : 'data-image="'.asset('assets/img/product-placeholder.webp') .'"';
    }
}

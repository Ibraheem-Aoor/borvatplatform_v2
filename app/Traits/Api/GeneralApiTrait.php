<?php
namespace App\Traits\Api;

trait GeneralApiTrait
{
    /**
     * @return JsonResponse
     */
    public function response($error_no , $data = [] , $message)
    {
        $data['status'] = $error_no;
        $data['message'] = $message;
        return response()->json($data , $error_no);
    }
}

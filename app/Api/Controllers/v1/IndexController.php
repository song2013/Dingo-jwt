<?php
namespace App\Api\Controllers\v1;

use App\Api\Controllers\BaseController;
use App\Api\Transformers\ApiUserTransformer;
use App\Models\Api\ApiUser;

class IndexController extends BaseController
{
    //

    public function show()
    {
        $apiUser=ApiUser::all();
        return $this->collection($apiUser,new ApiUserTransformer());
    }
}


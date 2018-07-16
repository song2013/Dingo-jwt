<?php
/**
 * Created by PhpStorm.
 * User: adminis
 * Date: 2018/7/16
 * Time: 16:26
 */
namespace App\Api\Transformers;

use  League\Fractal\TransformerAbstract;
use App\Models\Api\ApiUser;

class ApiUserTransformer extends TransformerAbstract
{
    public function transform(ApiUser $apiUser)
    {
        return [
            'id'      =>  $apiUser['id'],
            'name'    =>  $apiUser['name'],
            'last_time' =>  $apiUser['updated_at'],
        ];
    }
}
<?php

namespace app\common\model;


use app\common\model\user\User;

class Advice extends BaseModel
{
    protected $name = 'advice';


    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
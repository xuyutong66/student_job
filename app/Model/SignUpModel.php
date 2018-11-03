<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/26
 * Time: 11:41
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SignUpModel extends Model{

    public $timestamps;

    protected $table = 'sign_up';

    protected $fillable = [
        'user_id',
        'position_id',
        'company_status',
        'work_time_id',
        'sign_up_status',
        'remark',
        'create_time',
        'cancel_time'
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/27
 * Time: 21:05
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 学生模型
 * Class UserModel
 * @package App\Model
 */
class UserModel extends Model {

//    use SoftDeletes;

    protected $dates = ['deleted_at'];

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'user';

    protected $fillable = [
        'phone',
        'password',
        'type',
        'status',
        'deleted_at',
        'money',
        'payment_password',
        'create_time',
        'update_time',
        'token'
    ];

}
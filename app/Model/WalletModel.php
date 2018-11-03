<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/27
 * Time: 21:05
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 提现模型表
 * Class WalletModel
 * @package App\Model
 */
class WalletModel extends Model{

    public $timestamps;

    protected $table = 'take_money';

    protected $fillable = [
        'user_id',
        'name',
        'money',
        'type',
        'account_number',
        'create_time'
    ];
}
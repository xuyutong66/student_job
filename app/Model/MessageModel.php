<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/12
 * Time: 14:35
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 消息模型
 * Class MessageModel
 * @package App\Model
 */
class MessageModel extends Model{

    const CREATED_AT = 'create_time';

    protected $table = 'message';

    protected $fillable = [
        'title',
        'content',
        'type',
        'create_time'
    ];
}
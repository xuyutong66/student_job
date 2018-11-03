<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/12
 * Time: 14:44
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 反馈意见模型
 * Class FeedbackModel
 * @package App\Model
 */
class FeedbackModel extends Model{

    public $timestamps;

    protected $table = 'feedback';

    protected $fillable = [
        'parent_id',
        'user_id',
        'feed_title',
        'feed_status',
        'feed_time',
        'replay_content',
        'replay_time',
        'is_replay'
    ];
}
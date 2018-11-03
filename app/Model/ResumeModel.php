<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26
 * Time: 21:11
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 简历模型
 * Class ResumeModel
 * @package App\Model
 */
class ResumeModel extends Model{

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'resume';

    protected $fillable = [
        'user_id',
        'name',
        'header_img',
        'education_id',
        'sex',
        'height',
        'weight',
        'health_certificate',
        'is_delete',
        'create_time',
        'update_time'
    ];
}
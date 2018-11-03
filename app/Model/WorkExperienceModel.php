<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/26
 * Time: 21:44
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 工作经历模型
 * Class WorkExperienceModel
 * @package App\Model
 */
class WorkExperienceModel extends Model{

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'work_experience';

    protected $fillable = [
        'work_content',
        'work_year_id',
        'resume_id',
        'is_delete',
        'create_time',
        'update_time'
    ];
}
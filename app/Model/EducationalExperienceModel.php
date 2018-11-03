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
 * 教育经历表模型
 * Class EducationalExperienceModel
 * @package App\Model
 */
class EducationalExperienceModel extends Model{
    public $timestamps;

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'educational_experience';

    protected $fillable = [
        'school_name',
        'entre_school_year',
        'resume_id',
        'is_delete',
        'create_time',
        'update_time'
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/7/26
 * Time: 15:45
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model{

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'student_info';

    protected $fillable = [
        'user_id',
        'school',
        'major',
        'entre_school_year',
        'contact_phone',
        'student_identity',
        'name',
        'header_img',
        'create_time',
        'update_time'
    ];
}
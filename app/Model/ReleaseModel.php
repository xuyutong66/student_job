<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/2
 * Time: 20:32
 */
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 发布职位模型
 * Class ReleaseModel
 * @package App\Model
 */
class ReleaseModel extends Model{

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'release';

    protected $fillable = [
        'position_name',
        'lng',
        'lat',
        'company_id',
        'position_type',
        'position_money',
        'work_address',
        'position_describe',
        'recurite_person_num',
        'settlement_type',
        'personnel_require',
        'phone',
        'name',
        'work_time',
        'close_data',
        'other',
        'is_delete',
        'create_time',
        'update_time'
    ];

}
<?php
/**
 * Created by PhpStorm.
 * User: xuyutong
 * Date: 2018/8/2
 * Time: 19:57
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 公司信息模型
 * Class CompanyInfoModel
 * @package App\Model
 */
class CompanyInfoModel extends Model{

    const UPDATED_AT='update_time';

    const CREATED_AT = 'create_time';

    protected $table = 'company_info';

    protected $fillable = [
        'user_id',
        'business_license',
        'company_name',
        'company_logo',
        'contacts_name',
        'contact_phone',
        'industry',
        'company_address',
        'industry_profile',
        'company_money',
        'company_card',
        'alipay_password',
        'wenxin_password',
        'is_detele',
        'create_time',
        'update_time'
    ];

}
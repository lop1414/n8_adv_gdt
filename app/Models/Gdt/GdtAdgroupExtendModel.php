<?php

namespace App\Models\Gdt;


class GdtAdgroupExtendModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_adgroup_extends';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'adgroup_id';

    /**
     * @var string
     * 主键数据类型
     */
    public $keyType = 'string';

    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;

    /**
     * 属性访问器
     * @param $value
     * @return mixed
     */
    public function getCallbackConvertTypesAttribute($value){
        return json_decode($value);
    }

    /**
     * 属性修饰器
     * @param $value
     */
    public function setCallbackConvertTypesAttribute($value){
        $this->attributes['callback_convert_types'] = json_encode($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 关联回传策略模型 一对一
     */
    public function convert_callback_strategy(){
        return $this->belongsTo('App\Common\Models\ConvertCallbackStrategyModel', 'convert_callback_strategy_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 关联回传策略组模型 一对一
     */
    public function convert_callback_strategy_group(){
        return $this->belongsTo('App\Common\Models\ConvertCallbackStrategyGroupModel', 'convert_callback_strategy_group_id', 'id');
    }
}

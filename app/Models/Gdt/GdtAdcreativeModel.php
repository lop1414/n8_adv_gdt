<?php

namespace App\Models\Gdt;

class GdtAdcreativeModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_adcreatives';


    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;


    public function getSiteSetAttribute($value){
        return json_decode($value);
    }


    public function setSiteSetAttribute($value){
        $this->attributes['site_set'] = json_encode($value);
    }



    /**
     * @param $value
     * @return array
     * 属性访问器
     */
    public function getExtendsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * @param $value
     * 属性修饰器
     */
    public function setExtendsAttribute($value)
    {
        $this->attributes['extends'] = json_encode($value);
    }
}

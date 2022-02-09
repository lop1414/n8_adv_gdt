<?php

namespace App\Models\Gdt;

class GdtAdModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_ads';


    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;


    public function getAuditSpecAttribute($value){
        return json_decode($value);
    }


    public function setAuditSpecAttribute($value){
        $this->attributes['audit_spec'] = json_encode($value);
    }

}

<?php

namespace App\Models\Gdt;

class GdtAdgroupModel extends GdtModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'gdt_adgroups';


    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;


    public function getBidAmountAttribute($value){
        return $value/100;
    }




    public function getDailyBudgetAttribute($value){
        return $value/100;
    }




    public function getSiteSetAttribute($value){
        return json_decode($value);
    }


    public function setSiteSetAttribute($value){
        $this->attributes['site_set'] = json_encode($value);
    }


    public function getExtendsAttribute($value){
        return json_decode($value);
    }


    public function setExtendsAttribute($value){
        $this->attributes['extends'] = json_encode($value);
    }




    public function campaign(){
        return $this->hasOne('App\Models\Gdt\GdtCampaignModel', 'id', 'campaign_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联渠道模型 一对一
     */
    public function channel_unit(){
        return $this->hasOne('App\Models\ChannelUnitModel', 'adgroup_id', 'id');
    }
}

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
    public function channel_adgroup(){
        return $this->hasOne('App\Models\ChannelAdGroupModel', 'adgroup_id', 'id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联广告组扩展模型 一对一
     */
    public function gdt_adgroup_extends(){
        return $this->hasOne('App\Models\Gdt\GdtAdgroupExtendModel', 'adgroup_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联模型 广点通转化归因 一对一
     */
    public function gdt_conversion(){
        return $this->hasOne('App\Models\Gdt\GdtConversionModel', 'id', 'conversion_id');
    }
}

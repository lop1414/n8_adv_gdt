<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Common\Enums\MaterialTypeEnums;
use App\Enums\Gdt\GdtMaterialTypeEnum;
use App\Models\Gdt\GdtAdcreativeModel;
use App\Models\Gdt\GdtImageModel;
use App\Models\Gdt\GdtMaterialAdcreativeModel;
use App\Models\Gdt\GdtVideoModel;


class GdtMaterialAdcreativeService extends GdtService
{
    /**
     * OceanMaterialService constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
    }





    /**
     * @param array $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option = []){
        $gdtAdcreativeModel = new GdtAdcreativeModel();
        if(!empty($option['date'])){
            $date = Functions::getDate($option['date']);
            $gdtAdcreativeModel = $gdtAdcreativeModel->whereBetween('last_modified_time', ["{$date} 00:00:00", "{$date} 23:59:59"]);
        }
        $gdtAdcreatives = $gdtAdcreativeModel->get();


        foreach($gdtAdcreatives as $gdtAdcreative){

            $materialType = $this->sdk->getMaterialType($gdtAdcreative->adcreative_template_id);
            $fileId = 0;
            if($materialType == GdtMaterialTypeEnum::VIDEO){
                $materialType = MaterialTypeEnums::VIDEO;
                $fileId = $gdtAdcreative->extends->adcreative_elements->video;
            }

            if($materialType == GdtMaterialTypeEnum::IMAGE){
                $materialType = MaterialTypeEnums::IMAGE;
                if(isset($gdtAdcreative->extends->adcreative_elements->image)){
                    $fileId =  $gdtAdcreative->extends->adcreative_elements->image;
                }elseif (isset($gdtAdcreative->extends->adcreative_elements->image_list) && !empty($gdtAdcreative->extends->adcreative_elements->image_list)){
                    if(count($gdtAdcreative->extends->adcreative_elements->image_list) > 1){
                        throw new CustomException([
                            'code' => 'ADCREATIVE_COUNT_MAX',
                            'message' => '广告创意图片数量大于1',
                        ]);
                    }
                    $fileId =  $gdtAdcreative->extends->adcreative_elements->image_list[0];
                }
            }

            if($fileId == 0) continue;

            $material = $this->getMaterial($materialType, $fileId);
            if(!empty($material['material_id'])){
                $this->save([
                    'material_id' => $material['material_id'] ?? '',
                    'adcreative_id' => $gdtAdcreative->id ?? '',
                    'material_type' => $materialType,
                    'n8_material_id' => $material['n8_material_id'] ?? 0,
                    'signature' => $material['signature'] ?? '',
                ]);
            }
        }

        return true;
    }

    /**
     * @param $materialType
     * @param $fileId
     * @return array|null
     * @throws CustomException
     * 获取素材
     */
    protected function getMaterial($materialType, $fileId){
        $material = null;
        if($materialType == MaterialTypeEnums::IMAGE){
            $gdtImage = GdtImageModel::find($fileId);
            if(empty($gdtImage)){
                echo "找不到图片：{$fileId}\n";
                return null;
            }

            $imageModel = new \App\Models\Material\ImageModel();
            $image = $imageModel->whereRaw("
                signature = '{$gdtImage->signature}'
            ")->first();

            $n8MaterialId = 0;
            if(!empty($image)){
                $n8MaterialId = $image->id;
            }

            $material = [
                'material_id' => $gdtImage->id,
                'n8_material_id' => $n8MaterialId,
                'signature' => $gdtImage->signature,
            ];
        }elseif($materialType == MaterialTypeEnums::VIDEO){
            $gdtVideo = GdtVideoModel::find($fileId);
            if(empty($gdtVideo)){
                echo "找不到视频：{$fileId}\n";
                return null;
            }

            $videoModel = new \App\Models\Material\VideoModel();
            $video = $videoModel->whereRaw("
                (signature = '{$gdtVideo->signature}' OR source_signature = '{$gdtVideo->signature}')
            ")->first();

            $n8MaterialId = 0;
            if(!empty($video)){
                $n8MaterialId = $video->id;
            }

            $material = [
                'material_id' => $gdtVideo->id,
                'n8_material_id' => $n8MaterialId,
                'signature' => $gdtVideo->signature,
            ];

        }else{
            throw new CustomException([
                'code' => 'UNKNOWN_MATERIAL_TYPE',
                'message' => '未知的素材类型',
            ]);
        }

        return $material;
    }

    /**
     * @param $item
     * @return bool
     * 保存
     */
    protected function save($item){
        $gdtMaterialAdcreativeModel = new GdtMaterialAdcreativeModel();
        $gdtMaterialAdcreative = $gdtMaterialAdcreativeModel
            ->where('material_id', $item['material_id'])
            ->where('adcreative_id', $item['adcreative_id'])
            ->first();

        if(empty($gdtMaterialAdcreative)){
            $gdtMaterialAdcreative = new GdtMaterialAdcreativeModel();
        }

        $gdtMaterialAdcreative->material_id = $item['material_id'];
        $gdtMaterialAdcreative->creative_id = $item['creative_id'];
        $gdtMaterialAdcreative->material_type = $item['material_type'];
        $gdtMaterialAdcreative->n8_material_id = $item['n8_material_id'] ?? 0;
        $gdtMaterialAdcreative->signature = $item['signature'] ?? '';
        return $gdtMaterialAdcreative->save();
    }
}

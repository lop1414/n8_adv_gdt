<?php

namespace App\Enums\Gdt;

class GdtImageTypeEnum
{
    const IMAGE_TYPE_GIF = 'IMAGE_TYPE_GIF';
    const IMAGE_TYPE_JPG = 'IMAGE_TYPE_JPG';
    const IMAGE_TYPE_PNG = 'IMAGE_TYPE_PNG';
    const IMAGE_TYPE_SWF = 'IMAGE_TYPE_SWF';
    const IMAGE_TYPE_PSD = 'IMAGE_TYPE_PSD';
    const IMAGE_TYPE_BMP = 'IMAGE_TYPE_BMP';
    const IMAGE_TYPE_TIFF_INTEL = 'IMAGE_TYPE_TIFF_INTEL';
    const IMAGE_TYPE_TIFF_MOTOROLA = 'IMAGE_TYPE_TIFF_MOTOROLA';
    const IMAGE_TYPE_JPC = 'IMAGE_TYPE_JPC';
    const IMAGE_TYPE_JP2 = 'IMAGE_TYPE_JP2';
    const IMAGE_TYPE_JPX = 'IMAGE_TYPE_JPX';
    const IMAGE_TYPE_JB2 = 'IMAGE_TYPE_JB2';
    const IMAGE_TYPE_SWC = 'IMAGE_TYPE_SWC';
    const IMAGE_TYPE_IFF = 'IMAGE_TYPE_IFF';
    const IMAGE_TYPE_WBMP = 'IMAGE_TYPE_WBMP';
    const IMAGE_TYPE_XBM = 'IMAGE_TYPE_XBM';
    const IMAGE_TYPE_WEBP = 'IMAGE_TYPE_WEBP';
    const IMAGE_TYPE_FLV = 'IMAGE_TYPE_FLV';
    const IMAGE_TYPE_WAV = 'IMAGE_TYPE_WAV';
    const IMAGE_TYPE_MP3 = 'IMAGE_TYPE_MP3';
    const IMAGE_TYPE_MP4 = 'IMAGE_TYPE_MP4';
    const IMAGE_TYPE_AVI = 'IMAGE_TYPE_AVI';
    const IMAGE_TYPE_MOV = 'IMAGE_TYPE_MOV';

    /**
     * @var string
     * 名称
     */
    static public $name = '广点通图片类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::IMAGE_TYPE_GIF, 'name' => 'GIF'],
        ['id' => self::IMAGE_TYPE_JPG, 'name' => 'JPG'],
        ['id' => self::IMAGE_TYPE_PNG, 'name' => 'PNG'],
        ['id' => self::IMAGE_TYPE_SWF, 'name' => 'SWF'],
        ['id' => self::IMAGE_TYPE_PSD, 'name' => 'PSD'],
        ['id' => self::IMAGE_TYPE_BMP, 'name' => 'BMP'],
        ['id' => self::IMAGE_TYPE_TIFF_INTEL, 'name' => 'TIFF_INTEL'],
        ['id' => self::IMAGE_TYPE_TIFF_MOTOROLA, 'name' => 'TIFF_MOTOROLA'],
        ['id' => self::IMAGE_TYPE_JPC, 'name' => 'JPC'],
        ['id' => self::IMAGE_TYPE_JP2, 'name' => 'JP2'],
        ['id' => self::IMAGE_TYPE_JPX, 'name' => 'JPX'],
        ['id' => self::IMAGE_TYPE_JB2, 'name' => 'JB2'],
        ['id' => self::IMAGE_TYPE_SWC, 'name' => 'SWC'],
        ['id' => self::IMAGE_TYPE_IFF, 'name' => 'IFF'],
        ['id' => self::IMAGE_TYPE_WBMP, 'name' => 'WBMP'],
        ['id' => self::IMAGE_TYPE_XBM, 'name' => 'XBM'],
        ['id' => self::IMAGE_TYPE_WEBP, 'name' => 'WBMP'],
        ['id' => self::IMAGE_TYPE_FLV, 'name' => 'FLV'],
        ['id' => self::IMAGE_TYPE_WAV, 'name' => 'WAV'],
        ['id' => self::IMAGE_TYPE_MP3, 'name' => 'MP3'],
        ['id' => self::IMAGE_TYPE_MP4, 'name' => 'MP4'],
        ['id' => self::IMAGE_TYPE_AVI, 'name' => 'AVI'],
        ['id' => self::IMAGE_TYPE_MOV, 'name' => 'MOV'],
    ];
}

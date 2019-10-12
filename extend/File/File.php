<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/7
 * Time: 14:54
 */

namespace File;


use app\common\exception\UploadException;

class File
{
    /**
     * 存储目录
     * @var string
     */
    public static $savePath = 'uploads';


    /**
     * @param \think\File|null $file
     * @return string
     * @throws UploadException
     */
    public static function saveImage (\think\File $file = null)
    {
        if (empty($file))
            throw new UploadException('上传的文件不合法');

        $info = $file->move(ROOT_PATH . 'public' . DS . self::$savePath);


        if ($info->getError())
            throw new UploadException($info->getError());

        return '/' . self::$savePath . '/' . str_replace('\\', '/', $info->getSaveName());
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: chenlongmingob@outlook.com
 * Date: 2018/11/5
 * Time: 19:32
 */

namespace app\api\controller;


use app\ApiController;
use File\File;

class FileUpload extends ApiController
{
    public function image ($name = 'upload', $multiple = false)
    {
        if (!$multiple) {
            // 单文件
            $file = \request()->file($name);

            return ['uploaded' => true, 'url' => File::saveImage($file)];
        } else {
            // 多文件
            $names = explode(',', $name);
            $urls = [];
            foreach ($names as $n) {
                $file = \request()->file($n);
                $urls[] = File::saveImage($file);
            }

            return ['uploaded' => true, 'url' => implode(',', $urls)];
        }
    }
}
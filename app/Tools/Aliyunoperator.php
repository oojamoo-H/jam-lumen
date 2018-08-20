<?php

namespace Core\Tools;

use Faker\Factory as Faker;
class Aliyunoperator
{
    protected static $path = '';

    protected static $inst = NULL;

    protected static $extensions = ['jpg', 'jpeg', 'png', 'gif'];

    public static function instance()
    {
        if (NULL == self::$inst) {
            self::$inst = new Aliyunoperator();
            self::$path = STORAGE_PATH;
        }
        return self::$inst;
    }

    /**
     * @desc 保存上传的文件到本地磁盘
     * @desc 文件按照 '年-月' 分目录存储
     *
     * @param $tmpPath string 临时文件全路径
     * @param $fileName string 文件名
     *
     * @return \ReturnInfo
     */
    public function saveFile($tmpPath, $fileName)
    {
        try {
            $tmpFile = new File($tmpPath);

            if ($tmpFile->exists()) {
                $fileName = time() . '_' . Faker::create()->randomNumber(6) . '_' . $fileName;

                $savePath = sprintf("%s/%s", self::$path, $fileName);
                $saveFile = new File($savePath, TRUE);
                unset($saveFile);

                if ($tmpFile->copy($savePath)) {
                    chmod($savePath, 0777);
                    return ['result'=>true,'code'=>0,'msg'=>'success','data'=>$savePath];
                } else {
                    throw new \Exception('文件复制失败',-7001);
                }
            } else {
                throw new \Exception('文件上传本地服务器失败',-7002);

            }
        } catch (\Exception $e) {
            return ['code'=>$e->getCode(),'msg'=>$e->getMessage(),'result'=>false];
        }
    }

    /**
     * 存储到阿里云
     * @param $filePath
     * @return array
     */
    public function send($filePath)
    {
        $file = new File($filePath);
        if ($file->exists()) {
            $ext = strtolower($file->ext());
            if (in_array($ext, self::$extensions)) {
                try {
                    $url = Aliyun::send($filePath);
                    $file->delete();
                    return ['result'=>true,'code'=>0,'msg'=>'success','data'=>$url];
                } catch (\Exception $e) {
                    $file->delete();
                    ErrorHandler::setErrorInfo('图片上传阿里云失败', -7003);
                    return false;
                }
            } else {
                ErrorHandler::setErrorInfo("对不起,目前仅支持: %s 格式的图片", implode(", ", self::$extensions), -7004);
                return false;
            }
        } else {
            ErrorHandler::setErrorInfo('文件不存在', -7005);
            return false;
        }
    }
}
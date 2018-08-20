<?php

namespace Core\Tools;

class Pinyin
{
    protected static $inst = NULL;

    protected $file = NULL;

    protected $map = array();

    public static function instance()
    {
        if (NULL == self::$inst) {
            self::$inst = new Pinyin();
        }

        return self::$inst;
    }

    //php中文首字母转拼音
    public function getFirstString($str)
    {
        $str = trim($str);
        $newStr = "";
        $len = strlen($str);
        if ($len < 2) {
            return $str;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord($str[$i]) > 0x80) {
                $c = substr($str, $i, 3);
                $i = $i + 2;
                $newStr .= self::getFirstCharter($c);
            } else {
                $newStr .= strtoupper($str[$i]);
            }
        }
        return $newStr;
    }


    //php获取中文字符拼音首字母
    public function getFirstCharter($str)
    {
        if(empty($str)){return '';}
        $fchar=ord($str{0});
        if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
        $s1=iconv('UTF-8','gb2312',$str);
        $s2=iconv('gb2312','UTF-8',$s1);
        $s=$s2==$str?$s1:$str;
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';
        return null;
    }

    function __construct()
    {
        $filePath = STORAGE_PATH . '/files/pinyin.db';
        if (file_exists($filePath)) {
            $records = file($filePath);
            foreach ($records as $record) {
                $record = trim($record);
                $this->map[substr($record, 0, 3)] = substr($record, 4, strlen($record) - 3);
            }
        }
    }

    public function transform($str)
    {
        return $this->make($str, FALSE);
    }

    public function transformInitial($str)
    {
        return $this->make($str, TRUE);
    }

    protected function make($str, $isInitial = FALSE)
    {
        $str = trim($str);
        $newStr = "";
        $len = strlen($str);

        if ($len < 2) {
            return $str;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord($str[$i]) > 0x80) {
                $c = substr($str, $i, 3);
                $i = $i + 2;
                if (isset($this->map[$c])) {
                    if ($isInitial) {
                        $newStr .= $this->map[$c][0];
                    } else {
                        $newStr .= $this->map[$c];
                    }

                } else {
                    $newStr .= "_";
                }
            } else if (preg_match('/^[a-zA-Z0-9]$/', $str[$i])) {
                $newStr .= strtolower($str[$i]);
            } else {
                $newStr .= "_";
            }
        }

        return $newStr;
    }
}
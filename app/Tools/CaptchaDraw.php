<?php

namespace Core\Tools;

class CaptchaDraw
{

    public $fontFile;
    private $fontSize = 18;
    private $bgColor = [243, 251, 254]; //RGB

    public function __construct($fontFile)
    {
        $this->fontFile = $fontFile;
    }

    public function setFontSize($value)
    {
        $this->fontSize = $value;
        return $this;
    }

    public function setBgColor($red, $green, $blue)
    {
        $this->bgColor = [$red, $green, $blue];
        return $this;
    }

    /**
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线
     * 正弦型函数解析式：y=Asin(ωx+φ)+b
     * 各常数值对函数图像的影响：
     * A：决定峰值（即纵向拉伸压缩的倍数）
     * b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     * φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     * ω：决定周期（最小正周期T=2π/∣ω∣）
     */
    private function writeCurve($_image, $im_x, $im_y, $fontSize, $_color)
    {
        $A = mt_rand(1, $im_y / 2);                  // 振幅
        $b = mt_rand(-$im_y / 4, $im_y / 4);   // Y轴方向偏移量
        $f = mt_rand(-$im_y / 4, $im_y / 4);   // X轴方向偏移量
        $T = mt_rand($im_y * 1.5, $im_x * 2);  // 周期
        $w = (2 * M_PI) / $T;

        $px1 = 0;  // 曲线横坐标起始位置
        $px2 = mt_rand($im_x / 2, $im_x);  // 曲线横坐标结束位置
        for ($px = $px1; $px <= $px2; $px = $px + 0.9) {
            if ($w != 0) {
                $py = $A * sin($w * $px + $f) + $b + $im_y / 2;  // y = Asin(ωx+φ) + b
                $i = (int)(($fontSize - 6) / 4);
                while ($i > 0) {
                    imagesetpixel($_image, $px + $i, $py + $i, $_color);
                    $i--;
                }
            }
        }
        $A = mt_rand(1, $im_y / 2);                  // 振幅
        $f = mt_rand(-$im_y / 4, $im_y / 4);   // X轴方向偏移量
        $T = mt_rand($im_y * 1.5, $im_x * 2);  // 周期
        $w = (2 * M_PI) / $T;
        $b = $py - $A * sin($w * $px + $f) - $im_y / 2;
        $px1 = $px2 = $im_x;
        for ($px = $px1; $px <= $px2; $px = $px + 0.9) {
            if ($w != 0) {
                $py = $A * sin($w * $px + $f) + $b + $im_y / 2;  // y = Asin(ωx+φ) + b
                $i = (int)(($fontSize - 8) / 4);
                while ($i > 0) {
                    imagesetpixel($_image, $px + $i, $py + $i, $_color);
                    //这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出的（不用while循环）性能要好很多
                    $i--;
                }
            }
        }
    }

    private function writeNoise($_image, $im_x, $im_y)
    {
        for ($i = 0; $i < 10; $i++) {
            $noiseColor = imagecolorallocate($_image, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
            for ($j = 0; $j < 5; $j++) {
                imagestring($_image, 5, mt_rand(-10, $im_x), mt_rand(-10, $im_y), $this->code_set[mt_rand(0, 28)], $noiseColor);
            }
        }
    }

    public function drawCaptcha($word, $isDisplay = FALSE, $useCurve = TRUE, $useNoise = TRUE)
    {
        $len = strlen($word);
        $im_x = $len * $this->fontSize * 1.5 + $this->fontSize * 1.5;
        $im_y = $this->fontSize * 2;
        $_image = imagecreate($im_x, $im_y);

        imagecolorallocate($_image, $this->bgColor[0], $this->bgColor[1], $this->bgColor[2]);    // 设置背景
        $_color = imagecolorallocate($_image, mt_rand(1, 120), mt_rand(1, 120), mt_rand(1, 120));   // 验证码字体随机颜色

        if ($useNoise)
            $this->writeNoise($_image, $im_x, $im_y);
        if ($useCurve)
            $this->writeCurve($_image, $im_x, $im_y, $this->fontSize, $_color);

        // 绘验证码
        $codeNX = 0; // 验证码第N个字符的左边距
        for ($i = 0; $i < $len; $i++) {
            $code = strtoupper(substr($word, $i, 1));
            $codeNX += mt_rand($this->fontSize * 1.2, $this->fontSize * 1.6);
            imagettftext($_image, $this->fontSize, mt_rand(-40, 70), $codeNX, $this->fontSize * 1.5, $_color, $this->fontFile, $code);
        }

        // 输出图像
        if ($isDisplay) {
            header('Pragma: no-cache');
            header("content-type: image/JPEG");
            imageJPEG($_image);
            imagedestroy($_image);
        } else {
            ob_start();
            imageJPEG($_image);
            $data = ob_get_contents();
            ob_end_clean();
            imagedestroy($_image);
            return base64_encode($data);
//
//            $return = base64_encode($_image);
//            imagedestroy($_image);
//            return $return;
        }

    }

}


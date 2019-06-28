<?php

namespace common\helpers;

/**
 * Class FfmpegHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class FfmpegHelper
{
    /**
     * ffmege 启动
     *
     * 例如：/user/local/bin/ffmpeg
     *
     * 注意后面的空格
     * @var string
     */
    private static $ffmpegPath = 'ffmpeg ';

    /**
     * 转码
     *
     * @param $filePath
     * @param $fileNewPath
     */
    public static function transcoding($filePath, $fileNewPath)
    {
        exec(self::$ffmpegPath . "-i $filePath $fileNewPath");
    }

    /**
     * 获取视频截图
     *
     * @param string $filePath 视频文件(绝对路径)
     * @param string $imagePath 保存图片地址(绝对路径)
     * @param string $second 秒 例如 00:00:01
     */
    public static function imageResize($filePath, $imagePath, $second)
    {
        exec(self::$ffmpegPath . "-ss {$second}  -i {$filePath} {$imagePath}  -r 1 -vframes 1 -an -f mjpeg 1>/dev/null");
    }

    /**
     * 获取视频信息
     *
     * Array(
     *      [duration] => 00:02:28.63
     *      [seconds] => 148.63
     *      [start] => 0.000000
     *      [bitrate] => 1606
     *      [vcodec] => h264 (Main) (avc1 / 0x31637661)
     *      [vformat] => yuv420p
     *      [resolution] => 1280x720
     *      [width] => 1280
     *      [height] => 720
     *      [acodec] => aac (LC) (mp4a / 0x6134706D)
     *      [asamplerate] => 44100
     *      [play_time] => 148.63
     *      [size] => 29842292
     * )
     * @param $file
     * @resulturn array
     */
    public static function getVideoInfo($file)
    {
        ob_start();
        passthru(sprintf(self::$ffmpegPath . '-i "%s" 2>&1', $file));
        $videoInfo = ob_get_contents();
        ob_end_clean();

        // 使用输出缓冲，获取ffmpeg所有输出内容
        $result = [];
        // Duration: 00:33:42.64, start: 0.000000, bitrate: 152 kb/s
        if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $videoInfo, $matches)) {
            $result['duration'] = $matches[1]; // 视频长度
            $duration = explode(':', $matches[1]);
            $result['seconds'] = $duration[0] * 3600 + $duration[1] * 60 + $duration[2]; // 转为秒数
            $result['start'] = $matches[2]; // 开始时间
            $result['bitrate'] = $matches[3]; // bitrate 码率 单位kb
        }

        // 格式1：Stream #0:1: Video: rv20 (RV20 / 0x30325652), yuv420p, 352x288, 117 kb/s, 15 fps, 15 tbr, 1k tbn, 1k tbc
        // 格式2：Stream #0:1: Video: h264 (Main) (avc1 / 0x31637661), yuv420p(tv, smpte170m/bt709/bt709, progressive), 240x320, 475 kb/s, 29.84 fps, 29.97 tbr, 600 tbn, 1200 tbc (default)
        if (preg_match("/Video: (.*?), (.*?), (.*?), (.*?), (.*?)[,\s]/", $videoInfo, $matches)) {
            $result['vcodec'] = $matches[1];  // 编码格式

            try {
                $result['vformat'] = $matches[2]; // 视频格式
                list($width, $height) = explode('x', $matches[3]);
            } catch (\Exception $e) {
                $result['vformat'] = $matches[2] . ', ' . $matches[3] . ', ' . $matches[4]; // 视频格式
                $result['resolution'] = $matches[5]; // 分辨率
                list($width, $height) = explode('x', $matches[5]);
            }

            $result['width'] = $width;
            $result['height'] = $height;
        }

        // Stream #0:0: Audio: cook (cook / 0x6B6F6F63), 22050 Hz, stereo, fltp, 32 kb/s
        if (preg_match("/Audio: (.*), (\d*) Hz/", $videoInfo, $matches)) {
            $result['acodec'] = $matches[1];  // 音频编码
            $result['asamplerate'] = $matches[2]; // 音频采样频率
        }

        if (isset($result['seconds']) && isset($result['start'])) {
            $result['play_time'] = $result['seconds'] + $result['start']; // 实际播放时间
        }

        $result['size'] = filesize($file); // 视频文件大小

        // 基本信息
        // $videoInfo = iconv('gbk','utf8', $videoInfo);

        return $result;
    }
}
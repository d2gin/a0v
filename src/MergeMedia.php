<?php

namespace a0v;

use FFMpeg\FFMpeg;

class MergeMedia
{
    public static $ffmpeg = null;
    public static $instance = null;

    public function __construct($option = [])
    {
        $option       = array_merge([
            // 如果是win环境请务必添加ffmpeg的环境路径
            'ffmpeg.binaries'  => @$option['ffmpeg.binaries'] ?: 'ffmpeg',
            'ffprobe.binaries' => @$option['ffprobe.binaries'] ?: 'ffprobe',
        ], $option);
        self::$ffmpeg = FFMpeg::create($option);
    }

    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }
    /**
     * 音视频合并
     * @param $video
     * @param $audio
     */
    public function a0v($audio, $video, $output = true)
    {
        if ($output === true) {
            $output = "merge_{$video}";
        }
        // 资源列表
        $list = [$video, $audio];
        // 限定格式
        $format = new X264('copy', 'copy'); // 不要使用默认的x264，设了libx264会很慢，直接改写 使用copy
        // 创建ffmpeg实例
        $ffmpeg = self::$ffmpeg;
        // 必须多文件open
        $video = $ffmpeg->openAdvanced($list);
        try {
            $video->map(['0:v:0', '1:a:0'], $format, $output)->save();
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            return false;
        }
        return $output;
    }
}
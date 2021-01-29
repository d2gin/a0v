<?php
namespace a0v;
class X264 extends \FFMpeg\Format\Video\X264 {
    /**
     * 直接覆盖原有的format 使视频流支持copy属性
     * @return array
     */
    public function getAvailableVideoCodecs()
    {
        return array('libx264', 'copy');
    }
}
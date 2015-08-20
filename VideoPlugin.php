<?php

/**
 * This file is part of Herbie.
 *
 * (c) Thomas Breuss <www.tebe.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace herbie\plugin\video;

use Herbie;
use Twig_SimpleFunction;

class VideoPlugin extends Herbie\Plugin
{
    /**
     * @var int
     */
    private static $vimeoInstances = 0;

    /**
     * @var int
     */
    private static $youtubeInstances = 0;

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        $events = [];
        if ((bool)$this->config('plugins.config.video.twig', false)) {
            $events[] = 'onTwigInitialized';
        }
        if ((bool)$this->config('plugins.config.video.shortcode', true)) {
            $events[] = 'onShortcodeInitialized';
        }
        return $events;
    }

    public function onTwigInitialized($twig)
    {
        $twig->addFunction(
            new Twig_SimpleFunction('video_vimeo', [$this, 'vimeo'], ['is_safe' => ['html']])
        );
        $twig->addFunction(
            new Twig_SimpleFunction('video_youtube', [$this, 'youtube'], ['is_safe' => ['html']])
        );
    }

    public function onShortcodeInitialized($shortcode)
    {
        $shortcode->add('video_vimeo', [$this, 'vimeoShortcode']);
        $shortcode->add('video_youtube', [$this, 'youtubeShortcode']);
    }

    /**
     * @param string $id
     * @param int $width
     * @param int $height
     * @param int $responsive
     * @return string
     * @see http://embedresponsively.com/
     */
    public function vimeo($id, $width = 480, $height = 320, $responsive = 1)
    {
        self::$vimeoInstances++;
        $template = $this->config->get(
            'plugins.config.video.template.vimeo',
            '@plugin/video/templates/vimeo.twig'
        );
        return $this->render($template, [
            'src' => sprintf('//player.vimeo.com/video/%s', $id),
            'width' => $width,
            'height' => $height,
            'responsive' => $responsive,
            'class' => $responsive ? 'video-vimeo-responsive' : '',
            'instances' => self::$vimeoInstances
        ]);
    }

    /**
     * @param string $id
     * @param int $width
     * @param int $height
     * @param int $responsive
     * @return string
     * @see http://embedresponsively.com/
     */
    public function youtube($id, $width = 480, $height = 320, $responsive = 1)
    {
        self::$youtubeInstances++;
        $template = $this->config->get(
            'plugins.config.video.template.youtube',
            '@plugin/video/templates/youtube.twig'
        );
        return $this->render($template, [
            'src' => sprintf('//www.youtube.com/embed/%s?rel=0', $id),
            'width' => $width,
            'height' => $height,
            'responsive' => $responsive,
            'class' => $responsive ? 'video-youtube-responsive' : '',
            'instances' => self::$youtubeInstances
        ]);
    }

    public function youtubeShortcode($options)
    {
        $this->initOptions([
            'id' => '',
            'width' => 480,
            'height' => 320,
            'responsive' => 1
        ], $options);
        return call_user_func_array([$this, 'youtube'], $options);
    }

    public function vimeoShortcode($options)
    {
        $this->initOptions([
            'id' => '',
            'width' => 480,
            'height' => 320,
            'responsive' => 1
        ], $options);
        return call_user_func_array([$this, 'vimeo'], $options);
    }

}

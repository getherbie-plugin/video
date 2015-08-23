<?php

use Herbie\DI;
use Herbie\Hook;

class VideoPlugin
{
    /**
     * @var int
     */
    private static $vimeoInstances = 0;

    /**
     * @var int
     */
    private static $youtubeInstances = 0;

    private static $config;

    /**
     * @return array
     */
    public static function install()
    {
        static::$config = DI::get('Config');
        if ((bool)static::$config->get('plugins.config.video.twig', false)) {
            Hook::attach('twigInitialized', ['VideoPlugin', 'addTwigFunctions']);
        }
        if ((bool)static::$config->get('plugins.config.video.shortcode', true)) {
            Hook::attach('shortcodeInitialized', ['VideoPlugin', 'addShortcodes']);
        }
    }

    public static function addTwigFunctions($twig)
    {
        $twig->addFunction(
            new Twig_SimpleFunction('video_vimeo', ['VideoPlugin', 'vimeo'], ['is_safe' => ['html']])
        );
        $twig->addFunction(
            new Twig_SimpleFunction('video_youtube', ['VideoPlugin', 'youtube'], ['is_safe' => ['html']])
        );
    }

    public static function addShortcodes($shortcode)
    {
        $shortcode->add('video_vimeo', ['VideoPlugin', 'vimeoShortcode']);
        $shortcode->add('video_youtube', ['VideoPlugin', 'youtubeShortcode']);
    }

    /**
     * @param string $id
     * @param int $width
     * @param int $height
     * @param int $responsive
     * @return string
     * @see http://embedresponsively.com/
     */
    public static function vimeo($id, $width = 480, $height = 320, $responsive = 1)
    {
        self::$vimeoInstances++;
        $template = static::$config->get(
            'plugins.config.video.template.vimeo',
            '@plugin/video/templates/vimeo.twig'
        );
        return DI::get('Twig')->render($template, [
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
    public static function youtube($id, $width = 480, $height = 320, $responsive = 1)
    {
        self::$youtubeInstances++;
        $template = static::$config->get(
            'plugins.config.video.template.youtube',
            '@plugin/video/templates/youtube.twig'
        );
        return DI::get('Twig')->render($template, [
            'src' => sprintf('//www.youtube.com/embed/%s?rel=0', $id),
            'width' => $width,
            'height' => $height,
            'responsive' => $responsive,
            'class' => $responsive ? 'video-youtube-responsive' : '',
            'instances' => self::$youtubeInstances
        ]);
    }

    public static function youtubeShortcode($options)
    {
        $options = array_merge([
            'id' => '',
            'width' => 480,
            'height' => 320,
            'responsive' => 1
        ], $options);
        return call_user_func_array(['VideoPlugin', 'youtube'], $options);
    }

    public static function vimeoShortcode($options)
    {
        $options = array_merge([
            'id' => '',
            'width' => 480,
            'height' => 320,
            'responsive' => 1
        ], $options);
        return call_user_func_array(['VideoPlugin', 'vimeo'], $options);
    }

}

VideoPlugin::install();

# Herbie Video Plugin

`Video` ist ein [Herbie](http://github.com/getherbie/herbie) Plugin, mit dem du Videos von 
[YouTube](http://www.youtube.com) und [Vimeo](https://vimeo.com) in deine Website einbettest. Die eingebetteten
Videos sind responsive und passen sich der Grösse des Browserfensters automatisch an.


## Installation

Das Plugin installierst du via Composer.

	$ composer require getherbie/plugin-youtube

Danach aktivierst du das Plugin in der Konfigurationsdatei.

    plugins:
        enable:
            - video
            
            
## Konfiguration

Unter `plugins.config.video` stehen dir die folgenden Optionen zur Verfügung:

    # enable shortcodes
    shortcode: true
    
    # enable twig functions
    twig: false
    
    # template paths to twig templates 
    template:
        youtube: @plugin/video/templates/youtube.twig
        vimeo: @plugin/video/templates/vimeo.twig


## Anwendung

Nach der Installation stehen dir die Shortcodes `video_youtube` und `video_vimeo` zur Verfügung. Diese rufst du 
wie folgt auf:

    [video_youtube _0TfPpjDkWU width="480" height="320"]
    
    [video_vimeo 30459532 width="480" height="320"]


Mit dem Aktivieren der Twig-Funktionen kannst du diese auch in Layoutdateien einsetzen:

    {{ video_youtube("_0TfPpjDkWU", 480, 320) }}
    
    {{ video_vimeo("30459532", 480, 320) }}

Alternativ kannst du die Funktionen auch mit benannten Argumenten aufrufen.

    {{ video_youtube(id="_0TfPpjDkWU", width="480", height="320", responsive="1") }}
    
    {{ video_vimeo(id="30459532", width=480, height=320, responsive=1) }}


## Parameter

Name        | Beschreibung                          | Typ       | Default
:---------- | :------------------------------------ | :-------- | :------
id          | Die ID des Videos                     | string    |  
width       | Die Breite des Videos in Pixel        | int       | 480
height      | Die Höhe des Videos in Pixel          | int       | 320
responsive  | Definiert ob das Video responsiv ist  | bool      | true


## Demo

<https://herbie.tebe.ch/blog/2014/05/09-responsive-youtube-videos>  
<https://herbie.tebe.ch/blog/2014/05/21-vimeo-responsive-videos>

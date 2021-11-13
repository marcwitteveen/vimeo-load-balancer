<?php 

/**
 * This file is part of the VimeoLoadBalancer package.
 * 
 * (c) Marc Witteveen <marc.witteveen@gmail.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MarcWitteveen\VimeoLoadBalancer;

class VimeoLoadBalancer {

    var $videos = null; 

    var $autoplay = true;

    /**
     * Pick a random video from the given array
     * @return string A random video id from the videos array
     */
    private function get_random_video() 
    {
        $max = count($this->videos);
        $video_number = rand(0, $max-1);
        return $this->videos[$video_number];
    }

    /**
     * Pick a video that fits todays week day, e.g. 0 is Sunday, 6 is Saturday
     * @return string A video id picked based on the week day number from the videos array
     */
    private function get_weekday()
    {
        $video_number = date('w');
        return $this->videos[$video_number];
    }

    /**
     * Build the video player url
     * @param string $video_id The video id
     * @return string The vimeo video url
     */
    private function build_url(string $video_id)
    {

        $url = sprintf('https://player.vimeo.com/video/%s', $video_id);
        $url .= sprintf("?autoplay=%d&", ($this->autoplay)?1:0);
        return $url; 
    }

    /**
     * Generate bootstrap 4 html embed code
     * @param  mixed $generator 0...xxx for the selected video, static for the first video from the list
     * random for a random video from the list or weekday for a video representing the day of the week.
     * @param string $ratio The aspect ratio of the player "1by1", "4by3", "16by9", "21by9"
     * @return string The generated HTML code
     */
    private function bootstrap_4_embed($generator = 0, string $ratio = "16by9")
    {
        $url = $this->getUrl($generator);
        $html = sprintf("<div class='embed-responsive embed-responsive-%s'>", $ratio);
        if ($this->autoplay) {
            $html .= sprintf("<iframe class='embed-responsive-item' frameborder='0' src='%s' allow='autoplay; fullscreen; picture-in-picture' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>", $url);
        } else {
            $html .= sprintf("<iframe class='embed-responsive-item' frameborder='0' src='%s' fullscreen; picture-in-picture' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>", $url);
        }
        $html .= "</div>";
        return $html;
    }

    /**
     * Generate Twitter Bootstrap 5 embe code snippet
     * @param  mixed $generator 0...xxx for the selected video, static for the first video from the list
     * random for a random video from the list or weekday for a video representing the day of the week.
     * @param string $ratio The aspect ratio of the player "1x1", "4x3", "16x9", "21x0"
     * @return string HTML code snippet
     */
    private function bootstrap_5_embed($generator = 0, string $ratio = "16x9")
    {
        $url = $this->getUrl($generator);
        
        $html = sprintf("<div class='ratio ratio-%s'>", $ratio);
        if ($this->autoplay) {
            $html .= sprintf("<iframe class='embed-responsive-item' frameborder='0' src='%s' allow='autoplay; fullscreen; picture-in-picture' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>", $url);
        } else {
            $html .= sprintf("<iframe class='embed-responsive-item' frameborder='0' src='%s' fullscreen; picture-in-picture' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>", $url);
        }
        $html .= "</div>";
        return $html;
    }

    /**
     * Get the video ID of the video
     * @param  mixed $generator 0...xxx for the selected video, static for the first video from the list
     * random for a random video from the list or weekday for a video representing the day of the week.
     * @return string The found video ID
     */
    public function getVideoId($generator = 0) {
        if (is_int($generator)) {
            $video_id = (string) $this->videos[$generator];
        } elseif (strtolower($generator) === "static") {
            $video_id = (string) $this->videos[0];
        } elseif (strtolower($generator) === "random") {
            $video_id = (string) $this->get_random_video();
        } elseif (strtolower($generator) === "weekday") {
            $video_id = (string) $this->get_weekday();
        }
        return $video_id;
    }

    /**
     * Get the url
     * @param  mixed $generator 0...xxx for the selected video, static for the first video from the list
     * random for a random video from the list or weekday for a video representing the day of the week.
     * @return string The video url
     */
    public function getUrl($generator = 0)
    {
        $video_id = (string) $this->getVideoId($generator);
        return $this->build_url($video_id);
    }

    /**
     * Return the generated HTML
     * @param mixed $generator
     * @param string  $ratio Spect ratio of the video
     * @param string $framework The type of framework code to generate "bootstrap4"
     * @return string The generated HTML code
     */
    public function getHtml($generator = 0, string $ratio = "16by9", string $framework = "bootstrap4")
    {
        switch($framework) {
            case 'bootstrap4':
                return $this->bootstrap_4_embed($generator, $ratio);
                break;
            case 'bootstrap5':
                return $this->bootstrap_4_embed($generator, $ratio);
                break;
        }
    }
}
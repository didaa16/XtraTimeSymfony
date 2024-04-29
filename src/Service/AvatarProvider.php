<?php

namespace App\Service;

use Faker\Provider\Base as BaseProvider;
use InvalidArgumentException;
use RuntimeException;

class AvatarProvider extends BaseProvider {

    public static function avatarUrl($style = "adventurer", $size = null, $slug = null, $bg = null, $scale = null, $flip = null) {
        $baseUrl = "https://api.dicebear.com/8.x/";
        $url = $baseUrl . $style . "/png";

        $options = [
            'seed' => $slug,
            'background' => $bg,
            'scale' => $scale,
            'flip' => $flip
        ];

        if ($size) {
            $options['width'] = $size;
            $options['height'] = $size;
        }

        $params = http_build_query($options);

        if($params){
            $url .= "?" . $params;
        }

        return $url;
    }

    public static function avatar($dir = null, $fullPath = true, $style = "adventurer", $size = null, $slug = null, $bg = null, $scale = null, $flip = null) {
        $dir = is_null($dir) ? sys_get_temp_dir() : $dir; // GNU/Linux / OS X / Windows compatible

        if (!is_dir($dir) || !is_writable($dir)) {
            throw new InvalidArgumentException(sprintf('Cannot write to directory "%s"', $dir));
        }

        $filename = md5(uniqid('', true)) . '.png';
        $filepath = $dir . DIRECTORY_SEPARATOR . $filename;

        $url = static::avatarUrl($style, $size, $slug, $bg, $scale, $flip);

        if (function_exists('curl_exec')) {
            $fp = fopen($filepath, 'wb');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $success = curl_exec($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
            fclose($fp);
            curl_close($ch);

            if (!$success) {
                unlink($filepath);
                return false;
            }
        } elseif (ini_get('allow_url_fopen')) {
            copy($url, $filepath);
        } else {
            return new RuntimeException('The image formatter downloads an image from a remote HTTP server. Therefore, it requires that PHP can request remote hosts, either via cURL or fopen()');
        }

        return $fullPath ? $filepath : $filename;
    }
}

<?php

namespace App\Helpers;

class FileHelper
{

    /**
     * @param string $prefix
     * @return false|string
     */
    public static function createTmpFile(string $prefix = 'tmp_'): ?string
    {
        return tempnam(sys_get_temp_dir(), $prefix);
    }

    public static function hashFile($thumbnailPath)
    {
        return hash_file('sha1', $thumbnailPath);
    }

    public static function hash($string)
    {
        return hash('sha1', $string);
    }

}

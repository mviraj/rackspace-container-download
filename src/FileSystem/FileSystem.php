<?php

namespace App\FileSystem;

class FileSystem
{
    /**
     * Write a file on local filesystem
     *
     * @param string $filepath
     * @param string $contents
     * @return void
     */
    public static function write($filepath, $contents)
    {
        if (!empty(pathinfo($filepath, PATHINFO_EXTENSION))) {
            $parts = explode(DIRECTORY_SEPARATOR, $filepath);
            $file = array_pop($parts);
            $dir = implode(DIRECTORY_SEPARATOR, $parts);
    
            if (!is_dir($dir)) {
                mkdir($dir);
            }
    
            return file_put_contents($dir . DIRECTORY_SEPARATOR . $file, $contents);
        }
    }
}

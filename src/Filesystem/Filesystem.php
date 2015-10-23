<?php
namespace Gladeye\Lucent\Filesystem;

use Illuminate\Filesystem\Filesystem as Base;

class Filesystem extends Base {

    public function symlink($target, $link, $force = false) {
        if ($force) {
            return @symlink($target, $link);
        }

        return symlink($target, $link);
    }

    function relativePath($frompath, $topath) {
        $from = explode(DIRECTORY_SEPARATOR, $frompath); // Folders/File
        $to = explode(DIRECTORY_SEPARATOR, $topath); // Folders/File
        $relpath = '';

        $i = 0;
        // Find how far the path is the same
        while (isset($from[$i]) && isset($to[$i])) {
            if ($from[$i] != $to[$i]) {
                break;
            }

            $i++;
        }
        $j = count($from) - 1;
        // Add '..' until the path is the same
        while ($i <= $j) {
            if (!empty($from[$j])) {
                $relpath .= '..' . DIRECTORY_SEPARATOR;
            }

            $j--;
        }
        // Go to folder from where it starts differing
        while (isset($to[$i])) {
            if (!empty($to[$i])) {
                $relpath .= $to[$i] . DIRECTORY_SEPARATOR;
            }

            $i++;
        }

        // Strip last separator
        return substr($relpath, 0, -1);
    }
}

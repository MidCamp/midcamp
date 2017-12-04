<?php
/**
 * @file Hatter.php
 * Defines the composer install scripts for the Hatter theme integration.
 */
namespace MidCamp;

use Composer\Composer;
use Composer\Script\Event;

class Hatter
{
  public static function installAssets(Event $event) {
    echo "\nInstalling Hatter style-guide assets ...\n";

    /** @var Composer $composer */
    $composer = $event->getComposer();

    // Figure out the source path.
    $vendor_path = $composer->getConfig()->get('vendor-dir');
    $source_path = realpath("{$vendor_path}/midcamp/hatter/src/content/assets/");

    // Figure out the destination path.
    $extra = $composer->getPackage()->getExtra();
    $destination_path = realpath("{$vendor_path}/../{$extra['styleguide']['theme-path']}");

    // Find the names of directories in the source directory.
    foreach (glob("{$source_path}/*" , GLOB_ONLYDIR) as $source_dir) {
      $dir_name = static::_getPathTail($source_dir);
      $destination_dir = "{$destination_path}/{$dir_name}";

      if (!is_dir($destination_dir)) {
        mkdir($destination_dir, 0755, true);
      }

      // Copy all the directories in the source directory into the theme.
      if (is_dir($source_dir)) {
        foreach (glob("{$source_dir}/*") as $source) {
          $file_name = static::_getPathTail($source);

          $destination = "{$destination_dir}/{$file_name}";
          copy($source, $destination);
        }
      }
      else {
        echo "Could not copy $source_dir to {$destination_path}/{$dir_name}\n";
      }
    }
  }

  /**
   * Get the last bit in a file system path.
   * @param $path
   * @return string
   */
  private static function _getPathTail($path) {
    $parts = explode("/", $path);
    return end($parts);
  }
}
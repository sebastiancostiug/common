<?php
/**
 *
 * @package     slim-api-skeleton
 *
 * @subpackage  FileSystem
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2024 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    slim-api-skeleton
 * @see
 *
 * @since       2024-01-30
 *
 */

namespace common;

/**
 * Represents a file system component.
 */
class Filesystem
{
    /**
     * The base path for the file system.
     *
     * @var string
     */
    protected $basePath;

    /**
     * Class FileSystem
     *
     * Represents a file system component.
     */
    public function __construct()
    {
        $this->basePath = base_path();
    }

    /**
     * Check if a file or directory exists.
     *
     * @param string $path The path to the file or directory.
     *
     * @return boolean Returns true if the file or directory exists, false otherwise.
     */
    public function exists($path)
    {
        return file_exists($this->basePath . '/' . $path);
    }

    /**
     * Retrieves the contents of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The contents of the file, or false on failure.
     */
    public function get($path)
    {
        return file_get_contents($this->basePath . '/' . $path);
    }

    /**
     * Writes the given contents to a file at the specified path.
     *
     * @param string $path     The path of the file.
     * @param string $contents The contents to be written to the file.
     *
     * @return integer|false The number of bytes written to the file, or false on failure.
     */
    public function put($path, $contents)
    {
        return file_put_contents($this->basePath . '/' . $path, $contents);
    }

    /**
     * Prepends data to a file.
     *
     * If the file already exists, the data will be prepended to the existing content.
     * If the file does not exist, a new file will be created with the provided data.
     *
     * @param string $path The path to the file.
     * @param string $data The data to prepend to the file.
     *
     * @return boolean|integer The number of bytes written to the file, or false on failure.
     */
    public function prepend($path, $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * Appends data to a file.
     *
     * If the file already exists, the data will be appended to the existing content.
     * If the file does not exist, a new file will be created with the provided data.
     *
     * @param string $path The path to the file.
     * @param string $data The data to append to the file.
     *
     * @return boolean True on success, false on failure.
     */
    public function append($path, $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $this->get($path) . $data);
        }

        return $this->put($path, $data);
    }

    /**
     * Changes the permissions of a file or directory.
     *
     * @param string  $path The path to the file or directory.
     * @param integer $mode The new permissions to set. Default is 0777.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function chmod($path, $mode = 0777)
    {
        return chmod($this->basePath . '/' . $path, $mode);
    }

    /**
     * Changes the owner of a file or directory.
     *
     * @param string $path The path to the file or directory.
     * @param string $user The new owner of the file or directory.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function chown($path, $user)
    {
        return chown($this->basePath . '/' . $path, $user);
    }

    /**
     * Changes the group ownership of a file or directory.
     *
     * @param string $path  The path to the file or directory.
     * @param string $group The new group.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function chgrp($path, $group)
    {
        return chgrp($this->basePath . '/' . $path, $group);
    }

    /**
     * Copies a file from the specified path to the target path.
     *
     * @param string $path   The path of the file to be copied.
     * @param string $target The target path where the file will be copied to.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function copy($path, $target)
    {
        return copy($this->basePath . '/' . $path, $this->basePath . '/' . $target);
    }

    /**
     * Moves a file or directory to a new location.
     *
     * @param string $path   The path of the file or directory to be moved.
     * @param string $target The target path where the file or directory should be moved to.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function move($path, $target)
    {
        return rename($this->basePath . '/' . $path, $this->basePath . '/' . $target);
    }

        /**
     * Deletes a file or directory.
     *
     * @param string $path The path to the file or directory to be deleted.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function delete($path)
    {
        return unlink($this->basePath . '/' . $path);
    }

    /**
     * Creates a directory.
     *
     * @param string  $path      The path to the directory to be created.
     * @param integer $mode      The mode of the directory to be created.
     * @param boolean $recursive Allows the creation of nested directories specified in the path.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function makeDirectory($path, $mode = 0777, $recursive = false)
    {
        return mkdir($this->basePath . '/' . $path, $mode, $recursive);
    }

    /**
     * Removes a directory.
     *
     * @param string $path The path to the directory to be removed.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function removeDirectory($path)
    {
        return rmdir($this->basePath . '/' . $path);
    }

    /**
     * Returns the size of a file or directory.
     *
     * @param string $path The path to the file or directory.
     *
     * @return integer|false The size of the file or directory, or false on failure.
     */
    public function size($path)
    {
        return filesize($this->basePath . '/' . $path);
    }

    /**
     * Returns the last modified time of a file or directory.
     *
     * @param string $path The path to the file or directory.
     *
     * @return integer|false The last modified time of the file or directory, or false on failure.
     */
    public function lastModified($path)
    {
        return filemtime($this->basePath . '/' . $path);
    }

    /**
     * Returns the file type of a file or directory.
     *
     * @param string $path The path to the file or directory.
     *
     * @return string|false The file type of the file or directory, or false on failure.
     */
    public function type($path)
    {
        return filetype($this->basePath . '/' . $path);
    }

    /**
     * Returns the mime type of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The mime type of the file, or false on failure.
     */
    public function mimeType($path)
    {
        return mime_content_type($this->basePath . '/' . $path);
    }

    /**
     * Returns the file extension of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The file extension of the file, or false on failure.
     */
    public function extension($path)
    {
        return pathinfo($this->basePath . '/' . $path, PATHINFO_EXTENSION);
    }

    /**
     * Returns the file name of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The file name of the file, or false on failure.
     */
    public function name($path)
    {
        return pathinfo($this->basePath . '/' . $path, PATHINFO_FILENAME);
    }

    /**
     * Returns the file name of a file.
     *
     * @param string $path The path to the file.
     *
     * @return string|false The file name of the file, or false on failure.
     */
    public function basename($path)
    {
        return pathinfo($this->basePath . '/' . $path, PATHINFO_BASENAME);
    }

    /**
     * Checks if a file or directory is readable.
     *
     * @param string $path The path to the file or directory.
     *
     * @return boolean Returns true if the file or directory is readable, false otherwise.
     */
    public function isReadable($path)
    {
        return is_readable($this->basePath . '/' . $path);
    }

    /**
     * Checks if a file or directory is writable.
     *
     * @param string $path The path to the file or directory.
     *
     * @return boolean Returns true if the file or directory is writable, false otherwise.
     */
    public function isWritable($path)
    {
        return is_writable($this->basePath . '/' . $path);
    }

    /**
     * Checks if a file or directory is executable.
     *
     * @param string $path The path to the file or directory.
     *
     * @return boolean Returns true if the file or directory is executable, false otherwise.
     */
    public function isExecutable($path)
    {
        return is_executable($this->basePath . '/' . $path);
    }

    /**
     * Searches for files that match a given pattern.
     *
     * @param string  $pattern The pattern to search for.
     * @param integer $flags   Optional flags to modify the behavior of the globbing.
     *
     * @return array|false An array containing the matched files or directories, or false on failure.
     */
    public function glob($pattern, $flags = 0)
    {
        return glob($this->basePath . '/' . $pattern, $flags);
    }

    /**
     * Returns an array of files in the specified directory.
     *
     * @param string $directory The directory path.
     *
     * @return array An array of file paths.
     */
    public function files($directory)
    {
        return array_filter($this->glob($directory . '/*'), 'is_file');
    }

    /**
     * Returns an array of all files in the specified directory.
     *
     * @param string $directory The directory path.
     *
     * @return array The array of file paths.
     */
    public function allFiles($directory)
    {
        $glob = $this->glob($directory . '/*');

        if ($glob === false) {
            return [];
        }

        return array_filter($glob, 'is_file');
    }

    /**
     * Returns an array of directories within the specified directory.
     *
     * @param string $directory The directory path.
     *
     * @return array An array of directories.
     */
    public function directories($directory)
    {
        return array_filter($this->glob($directory . '/*'), 'is_dir');
    }

    /**
     * Returns an array of all directories within the specified directory.
     *
     * @param string $directory The directory path.
     *
     * @return array An array of directory paths.
     */
    public function allDirectories($directory)
    {
        $glob = $this->glob($directory . '/*');

        if ($glob === false) {
            return [];
        }

        return array_filter($glob, 'is_dir');
    }

    /**
     * Creates a directory recursively.
     *
     * @param string  $directory The directory path to create.
     * @param integer $mode      The permissions mode for the directory (default: 0777).
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function makeDirectoryRecursive($directory, $mode = 0777)
    {
        return mkdir($this->basePath . '/' . $directory, $mode, true);
    }

    /**
     * Deletes a directory and its contents.
     *
     * @param string  $directory The path to the directory to be deleted.
     * @param boolean $preserve  Whether to preserve the directory itself or not.
     *                           If set to true, only the contents of the directory will be deleted.
     *                           If set to false, the directory and its contents will be deleted.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function deleteDirectory($directory, $preserve = false)
    {
        if (! $preserve) {
            return $this->removeDirectory($directory);
        }

        $files = $this->allFiles($directory);

        foreach ($files as $file) {
            $this->delete($file);
        }

        $directories = $this->allDirectories($directory);

        foreach ($directories as $directory) {
            $this->deleteDirectory($directory, $preserve);
        }

        return $this->removeDirectory($directory);
    }

    /**
     * Cleans a directory by deleting all its contents.
     *
     * @param string $directory The path to the directory to be cleaned.
     *
     * @return boolean True if the directory was successfully cleaned, false otherwise.
     */
    public function cleanDirectory($directory)
    {
        return $this->deleteDirectory($directory, true);
    }

    /**
     * Checks if a given path is a file.
     *
     * @param string $path The path to check.
     *
     * @return boolean Returns true if the path is a file, false otherwise.
     */
    public function isFile($path)
    {
        return is_file($this->basePath . '/' . $path);
    }

    /**
     * Checks if a given path is a directory.
     *
     * @param string $path The path to check.
     *
     * @return boolean Returns true if the path is a directory, false otherwise.
     */
    public function isDirectory($path)
    {
        return is_dir($this->basePath . '/' . $path);
    }

    /**
     * Check if a given path is a symbolic link.
     *
     * @param string $path The path to check.
     *
     * @return boolean Returns true if the path is a symbolic link, false otherwise.
     */
    public function isLink($path)
    {
        return is_link($this->basePath . '/' . $path);
    }

    /**
     * Creates a symbolic link from the target file/directory to the specified link.
     *
     * @param string $target The target file/directory path.
     * @param string $link   The path of the symbolic link to be created.
     *
     * @return boolean Returns true on success, false on failure.
     */
    public function link($target, $link)
    {
        return symlink($this->basePath . '/' . $target, $this->basePath . '/' . $link);
    }

    /**
     * Retrieves the required file at the specified path.
     *
     * @param string $path The path to the required file.
     *
     * @return mixed|false The required file if it exists, false otherwise.
     */
    public function getRequire($path)
    {
        if ($this->exists($path)) {
            return require $this->basePath . '/' . $path;
        }

        return false;
    }
}

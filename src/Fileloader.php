<?php
/**
 *
 * @package     Common
 *
 * @subpackage  Fileloader
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2024 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    common classes
 *
 * @since       2024-02-01
 *
 */

namespace common;

use common\Collection;
use common\Filesystem;

/**
 * Class Fileloader
 *
 * This class is responsible for loading files.
 */
class Fileloader
{
    /**
     * @var Filesystem $files The Filesystem instance.
     */
    protected $files;

    /**
     * The path of the file to be loaded.
     *
     * @var string
     */
    protected $path;

    /**
     * The array of JSON file paths.
     *
     * @var array
     */
    protected $jsonPaths = [];

    /**
     * The array that stores the paths of the loaded JSON files.
     *
     * @var array
     */
    protected $jsonPathsLoaded = [];

    /**
     * The hints array used by the Fileloader class.
     *
     * @var array
     */
    protected $hints = [];

    /**
     * The default locale for the file loader.
     *
     * @var string
     */
    protected $defaultLocale;

    /**
     * The fallback locale for the file loader.
     *
     * @var string
     */
    protected $fallbackLocale;

    /**
     * Fileloader constructor.
     *
     * @param Filesystem $files The Filesystem instance.
     * @param string     $path  The path of the file to be loaded.
     *
     * @return void
     */
    public function __construct(Filesystem $files, $path)
    {
        $this->files = $files;
        $this->path = $path;
    }

    /**
     * Load the translation lines for a given locale, group, and namespace.
     *
     * @param string      $locale    The locale of the translation lines.
     * @param string      $group     The translation group.
     * @param string|null $namespace The translation namespace (optional).
     *
     * @return array The loaded translation lines.
     */
    public function load($locale, $group, $namespace = null)
    {
        $lines = [];

        if (is_null($namespace) || $namespace === '*') {
            $lines = $this->loadPath($this->path, $locale, $group);
        } elseif (str_contains($namespace, '::')) {
            [$namespace, $group] = explode('::', $namespace);
            $lines = $this->loadNamespaced($locale, $group, $namespace);
        } else {
            $lines = $this->loadNamespaceOverrides($locale, $group, $namespace);
        }

        $lines = new Collection($lines);

        return $lines->dot()->toArray();
    }

    /**
     * Load the language file from the specified path, locale, and group.
     *
     * @param string $path   The base path of the language files.
     * @param string $locale The locale of the language file.
     * @param string $group  The group of the language file.
     *
     * @return array The loaded language file as an array, or an empty array if the file does not exist.
     */
    protected function loadPath($path, $locale, $group)
    {
        $fullPath = "{$path}/{$locale}/{$group}.php";

        return $this->files->getRequire($fullPath);
    }

    /**
     * Load the namespaced translation lines for a given locale, group, and namespace.
     *
     * @param string $locale    The locale of the translation lines.
     * @param string $group     The translation group.
     * @param string $namespace The namespace of the translation lines.
     *
     * @return array The loaded translation lines.
     */
    protected function loadNamespaced($locale, $group, $namespace)
    {
        $lines = [];

        if (isset($this->hints[$namespace])) {
            foreach ($this->hints[$namespace] as $hint) {
                $lines = array_replace_recursive($lines, $this->loadPath($hint, $locale, $group));
            }
        }

        return $lines;
    }

    /**
     * Load namespace overrides for a given locale, group, and namespace.
     *
     * @param string $locale    The locale to load.
     * @param string $group     The group to load.
     * @param string $namespace The namespace to load.
     *
     * @return array The loaded lines.
     */
    protected function loadNamespaceOverrides($locale, $group, $namespace)
    {
        $lines = [];

        if (isset($this->hints[$namespace])) {
            foreach ($this->hints[$namespace] as $hint) {
                $lines = array_replace_recursive($lines, $this->loadPath($hint, $locale, $group));
            }
        }

        $lines = array_replace_recursive($lines, $this->loadPath("{$this->path}/packages/{$namespace}", $locale, $group));

        return $lines;
    }

    /**
     * Adds a JSON path to the Fileloader.
     *
     * @param string $path The path to the JSON file.
     *
     * @return void
     */
    public function addJsonPath($path)
    {
        $this->jsonPaths[] = $path;
    }

    /**
     * Loads JSON paths for a specific locale and group.
     *
     * @param string $locale The locale to load JSON paths for.
     * @param string $group  The group to load JSON paths for.
     *
     * @return array The loaded JSON paths.
     */
    public function loadJsonPaths($locale, $group)
    {
        $lines = [];

        foreach ($this->jsonPaths as $jsonPath) {
            if (in_array($jsonPath, $this->jsonPathsLoaded)) {
                continue;
            }

            $fullPath = "{$jsonPath}/{$locale}/{$group}.json";

            if ($this->files->exists($fullPath)) {
                $lines = array_replace_recursive($lines, $this->files->getRequire($fullPath));
            }

            $this->jsonPathsLoaded[] = $jsonPath;
        }

        return $lines;
    }

    /**
     * Adds a namespace and its corresponding hint to the file loader.
     *
     * @param string $namespace The namespace to be added.
     * @param string $hint      The hint for the namespace.
     *
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace][] = $hint;
    }

    /**
     * Get the namespaces registered in the file loader.
     *
     * @return array The registered namespaces.
     */
    public function namespaces()
    {
        return $this->hints;
    }

    /**
     * Checks if a namespace exists in the file loader.
     *
     * @param string $namespace The namespace to check.
     *
     * @return boolean Returns true if the namespace exists, false otherwise.
     */
    public function hasNamespace($namespace)
    {
        return isset($this->hints[$namespace]);
    }

    /**
     * Retrieves the JSON paths.
     *
     * @return array The JSON paths.
     */
    public function getJsonPaths()
    {
        return $this->jsonPaths;
    }

    /**
     * Sets the JSON paths for the Fileloader.
     *
     * @param array $paths The array of JSON paths.
     *
     * @return void
     */
    public function setJsonPaths(array $paths)
    {
        $this->jsonPaths = $paths;
    }

    /**
     * Get the default locale.
     *
     * @return string The default locale.
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Set the default locale for the Fileloader.
     *
     * @param string $locale The default locale to set.
     *
     * @return void
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }
    /**
     * Retrieves the fallback locale used by the Fileloader.
     *
     * @return string The fallback locale.
     */
    public function getFallbackLocale()
    {
        return $this->fallbackLocale;
    }

    /**
     * Set the fallback locale for the file loader.
     *
     * @param string $locale The fallback locale to set.
     *
     * @return void
     */
    public function setFallbackLocale($locale)
    {
        $this->fallbackLocale = $locale;
    }
}

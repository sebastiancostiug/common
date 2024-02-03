<?php
/**
 *
 * @package     Common
 *
 * @subpackage  Collection
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    common classes
 *
 * @since       2023-11-29
 *
 */

namespace common;

/**
 * Represents a collection of items.
 */
class Collection
{
    /**
     * The items in the collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new Collection instance.
     *
     * @param array $items The items to initialize the collection with.
     *
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Add an item to the collection.
     *
     * @param mixed $item The item to add.
     *
     * @return self
     */
    public function add(mixed $item)
    {
        $this->items[] = $item;

        return new static($this->items);
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param mixed $key The key of the item to remove.
     *
     * @return self
     */
    public function remove(mixed $key)
    {
        unset($this->items[$key]);

        return new static($this->items);
    }

    /**
     * Get an item from the collection by key.
     *
     * @param mixed $key The key of the item to retrieve.
     *
     * @return mixed|null The item value if found, null otherwise.
     */
    public function get(mixed $key)
    {
        return $this->items[$key] ?? null;
    }

    /**
     * Set an item in the collection by key.
     *
     * @param mixed $key   The key of the item to set.
     * @param mixed $value The value of the item to set.
     *
     * @return self
     */
    public function set(mixed $key, mixed $value)
    {
        $this->items[$key] = $value;

        return new static($this->items);
    }

    /**
     * Get all items in the collection.
     *
     * @return array The items in the collection.
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Apply a callback function to each item in the collection and return a new collection.
     *
     * @param callable $callback The callback function to apply to each item.
     *
     * @return Collection The new collection with the modified items.
     */
    public function map(callable $callback)
    {
        return new static(array_map($callback, $this->items));
    }

    /**
     * Filter the collection using a callback function and return a new collection.
     *
     * @param callable $callback The callback function to filter the items.
     *
     * @return Collection The new collection with the filtered items.
     */
    public function filter(callable $callback)
    {
        return new static(array_filter($this->items, $callback));
    }

    /**
     * Apply a callback function to each item in the collection.
     *
     * @param callable $callback The callback function to apply to each item.
     *
     * @return self
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }

        return new static($this->items);
    }

    /**
     * Get the number of items in the collection.
     *
     * @return integer The number of items in the collection.
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Collapse the collection into a single array.
     *
     * @return array The collapsed array.
     */
    public function collapse()
    {
        $result = [];
        foreach ($this->items as $item) {
            $result = array_merge($result, $item);
        }

        return $result;
    }

    /**
     * Flattens the collection by merging any nested arrays into a single array.
     *
     * @return array The flattened collection.
     */
    public function flatten()
    {
        $result = [];
        foreach ($this->items as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $item);
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Returns a new collection with only unique items.
     *
     * @return array The collection with unique items.
     */
    public function unique()
    {
        return array_unique($this->items);
    }

    /**
     * Converts the collection to a JSON string.
     *
     * @return string The JSON representation of the collection.
     */
    public function toJson()
    {
        return json_encode($this->items);
    }

    /**
     * Converts the collection to an array.
     *
     * @return array The collection as an array.
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Checks if the collection is empty.
     *
     * @return boolean True if the collection is empty, false otherwise.
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Checks if the collection is not empty.
     *
     * @return boolean True if the collection is not empty, false otherwise.
     */
    public function isNotEmpty()
    {
        return !empty($this->items);
    }

    /**
     * Returns the first item in the collection.
     *
     * @return mixed The first item in the collection.
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * Returns the last item in the collection.
     *
     * @return mixed The last item in the collection.
     */
    public function last()
    {
        return end($this->items);
    }

    /**
     * Multidimentional array search of n'th depth
     *
     * @param mixed $needle The item to search for.
     *
     * @return boolean
     */
    public function contains(mixed $needle)
    {
        foreach ($this->items as $item) {
            if ($item === $needle) {
                return true;
            }

            if (is_array($item) && $this->contains($needle, $item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if the collection has a given key.
     *
     * @param mixed $key The key to check for.
     *
     * @return boolean True if the collection has the key, false otherwise.
     */
    public function has(mixed $key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Search the collection for a given value and return the corresponding key if successful.
     *
     * @param  mixed   $value  The value to search for.
     * @param  boolean $strict Whether to use strict comparison.
     *
     * @return mixed|boolean
     */
    public function search(mixed $value, $strict = false)
    {
        if (is_callable($value)) {
            foreach ($this->items as $key => $item) {
                if ($value($item, $key)) {
                    return $key;
                }
            }

            return false;
        }

        return array_search($value, $this->items, $strict);
    }

    /**
     * implode
     *
     * @param string $glue The string to use as a glue.
     *
     * @return string
     */
    public function implode(string $glue)
    {
        return implode($glue, $this->items);
    }

    /**
     * Flips the keys and values of the collection.
     *
     * @return array The flipped collection.
     */
    public function flip()
    {
        return array_flip($this->items);
    }

    /**
     * Sort the collection using a callback function.
     *
     * @param callable $callback The callback function to use for sorting.
     *
     * @return Collection The sorted collection.
     */
    public function sort(callable $callback)
    {
        $items = $this->items;
        usort($items, $callback);

        return new static($items);
    }

    /**
     * Sort the collection in ascending order.
     *
     * @return Collection The sorted collection.
     */
    public function sortAsc()
    {
        $items = $this->items;
        sort($items);

        return new static($items);
    }

    /**
     * Sort the collection in descending order.
     *
     * @return Collection The sorted collection.
     */
    public function sortDesc()
    {
        $items = $this->items;
        rsort($items);

        return new static($items);
    }

    /**
     * Returns an array of all the keys in the collection.
     *
     * @return array The keys of the collection.
     */
    public function keys()
    {
        return array_keys($this->items);
    }

    /**
     * Returns an array of all the values in the collection.
     *
     * @return array The values of the collection.
     */
    public function values()
    {
        return array_values($this->items);
    }

    /**
     * Calculates the difference between the collection and the given array.
     *
     * @param array $array The array to compare against.
     * @return array The difference between the collection and the given array.
     */
    public function diff(array $array)
    {
        return array_diff($this->items, $array);
    }

    /**
     * Returns the maximum value in the collection.
     *
     * If a callback function is provided, it will be applied to each item in the collection before determining the maximum value.
     *
     * @param callable|null $callback The callback function to apply to each item in the collection.
     *
     * @return mixed The maximum value in the collection.
     */
    public function max($callback = null)
    {
        if ($callback === null) {
            return max($this->items);
        }

        $values = array_map($callback, $this->items);

        return max($values);
    }
    /**
     * Pad collection to the specified length with a value.
     *
     * @template TPadValue
     *
     * @param  integer $size  The size to pad to.
     * @param  mixed   $value The value to pad with.
     *
     * @return static<int, TValue|TPadValue>
     */
    public function pad(int $size, mixed $value): static
    {
        return new static(array_pad($this->items, $size, $value));
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Calculates the intersection between the collection and the given array.
     *
     * @param array $array The array to compare against.
     * @return array The intersection between the collection and the given array.
     */
    public function intersect(array $array)
    {
        return array_intersect($this->items, $array);
    }

    /**
     * Returns a new collection with all the items except the ones with the specified keys.
     *
     * @param array $keys The keys to exclude from the collection.
     * @return array The new collection with the excluded items.
     */
    public function except(array $keys)
    {
        return array_diff_key($this->items, array_flip($keys));
    }

    /**
     * Group the collection by a given key.
     *
     * @param string|callable $groupBy      The key or callback function to group by.
     * @param boolean         $preserveKeys Whether to preserve the original keys of the collection.
     *
     * @return Collection The grouped collection.
     */
    public function group($groupBy, $preserveKeys = false)
    {
        $groups = [];

        foreach ($this->items as $key => $item) {
            $groupKey = is_callable($groupBy) ? $groupBy($item) : $item[$groupBy];

            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = new static();
            }

            if ($preserveKeys) {
                $groups[$groupKey][$key] = $item;
            } else {
                $groups[$groupKey][] = $item;
            }
        }

        return new static($groups);
    }

    /**
     * Create a new collection consisting of every n-th element.
     *
     * @param  integer $step   The step to use.
     * @param  integer $offset The offset to use.
     *
     * @return static
     */
    public function nth($step, $offset = 0)
    {
        $new = [];

        $position = 0;

        foreach ($this->items as $item) {
            if ($position % $step === $offset) {
                $new[] = $item;
            }

            $position++;
        }

        return new static($new);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param  mixed $value The item to push.
     * @param  mixed $key   The key to push.
     *
     * @return $this
     */
    public function prepend(mixed $value, mixed $key = null)
    {
        if ($key === null) {
            array_unshift($this->items, $value);
        } else {
            $this->items = [$key => $value] + $this->items;
        }

        return $this;
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param  integer $offset      The offset to use.
     * @param  integer $length      The length to use.
     * @param  array   $replacement The replacement to use.
     *
     * @return static
     */
    public function splice($offset, $length = null, array $replacement = [])
    {
        if (func_num_args() === 1) {
            return new static(array_splice($this->items, $offset));
        }

        return new static(array_splice($this->items, $offset, $length, $replacement));
    }

    /**
     * Slice the underlying collection array.
     *
     * @param  integer $offset The offset to use.
     * @param  integer $length The length to use.
     *
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Skip the first {$count} items.
     *
     * @param  integer $count The number of items to skip.
     *
     * @return static
     */
    public function skip($count)
    {
        return $this->slice($count);
    }

    /**
     * Skip items in the collection until the given condition is met.
     *
     * @param  mixed $value The value to skip to.
     *
     * @return static
     */
    public function skipTo(mixed $value)
    {
        $skip = true;
        $new = [];

        foreach ($this->items as $item) {
            if ($skip) {
                if ($item === $value) {
                    $skip = false;
                }
            } else {
                $new[] = $item;
            }
        }

        return new static($new);
    }

    /**
     * Combines the values of the collection with the given array and returns a new collection.
     *
     * @param array $values The array to combine with the collection values.
     *
     * @return static A new collection with the combined values.
     */
    public function combine(array $values) : static
    {
        return new static(array_combine($this->items, $values));
    }

    /**
     * Merge the given array of values with the current collection.
     *
     * @param array|self $values The values to merge.
     *
     * @return static The merged collection.
     */
    public function merge(array|self $values) : static
    {
        if ($values instanceof self) {
            $values = $values->toArray();
        }

        return new static(array_merge($this->items, $values));
    }

    /**
     * Flattens the collection into a single-level array using dot notation.
     *
     * @return static The flattened collection.
     */
    public function dot():static
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, (new static($value))->dot());
            } else {
                $result[$key] = $value;
            }
        }

        return new static($result);
    }
}

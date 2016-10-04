<?php
namespace AKlump\Data;


class Data implements DataInterface
{
    /**
     * Extend this class and alter the default value of $pathSeparator if want
     * to use a different separator for your path strings.
     *
     * @var string
     */
    protected $pathSeparator = '.';

    /**
     * @inheritdoc
     */
    public function get($subject, $path, $defaultValue = null, $valueCallback = null)
    {
        if (empty($subject)) {
            return $defaultValue;
        }

        $this->validate($subject, $path, $defaultValue);
        $key = array_shift($path);
        $base = $subject;

        if (is_array($base)) {
            $base = isset($base[$key]) ? $base[$key] : $defaultValue;
        }
        elseif (is_object($base)) {
            $base = clone $base;
            if (isset($base->{$key})) {
                $base = $base->{$key};
            }
            else {
                $processed = false;
                foreach ($this->childGetters() as $method => $callback) {
                    if (method_exists($base, $method)) {
                        $base = $callback($base, $key, $defaultValue);
                        $processed = true;
                        break;
                    }
                }
                $base = $processed ? $base : $defaultValue;
            }
        }
        if (count($path) === 0) {
            return $this->postGet($base, $defaultValue, $valueCallback);
        }
        $function = __FUNCTION__;

        return $this->{$function}($base, implode($this->pathSeparator, $path), $defaultValue, $valueCallback);
    }

    protected function validate($subject, &$path, $defaultValue)
    {
        // Convert ints/floats to a string, if possible
        $path = is_numeric($path) && strval($path) == $path ? strval($path) : $path;

        // Explode strings
        $path = is_string($path) ? explode($this->pathSeparator, $path) : $path;
        if (!is_array($path)) {
            throw new \InvalidArgumentException("\$path must be an array of $this->pathSeparator separated string.");
        }
    }

    /**
     * Return an array of possible methods to call on objects for getting
     * property values.
     *
     * Extend this method as needed if you have child classes using
     * methods other than 'get' for extracting a propery value.
     *
     * By returning an array of methods, it allows this class to support
     * non-homomogenous objects under a commone banner.  The first matched
     * method on the child will be used, subsequent methods will not be called.
     *
     * @return array
     *   - Keys are the methods to call on the child class
     *   - Values are callbacks which need to return a value, arguments
     *   received are $object, $property, $default.
     */
    protected function childGetters()
    {
        return array(
            'get' => function ($childObject, $property, $defaultValue) {
                return $childObject->get($property, $defaultValue);
            },
        );
    }

    /**
     * Post process the get method.
     *
     * @param mixed    $value The discovered value, or default.
     * @param mixed    $defaultValue
     * @param Callable $valueCallback
     *
     * @return mixed
     */
    protected function postGet($value, $defaultValue, $valueCallback)
    {
        if (is_callable($valueCallback)) {
            $value = $valueCallback($value, $defaultValue);
        }

        return $value;
    }
}

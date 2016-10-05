<?php
namespace AKlump\Data;

interface DataInterface
{
    /**
     * Gets the value at path of subject. If the resolved value is undefined,
     * the defaultValue is returned in its place.
     *
     * If $subject is empty, regardless of path, $defaultValue is returned.
     *
     * @param mixed        $subject
     * @param string|array $path
     * @param mixed        $defaultValue  Defaults to null.
     * @param Callable     $valueCallback Defaults to null. Optional callback
     *                                    if the value does not match
     *                                    $defaultValue, receives the
     *                                    arguments: $value, $defaultValue.
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException If the path is invalid.
     */
    public function get($subject, $path, $defaultValue = null, $valueCallback = null);

    /**
     * Set a value on $subject, including parents as needed.
     *
     * @param array|object $subject       The base subject.
     * @param string       $path
     * @param mixed        $value         The value to set.
     * @param null|mixed   $childTemplate If an child element must be created
     *                                    to
     *                                    establish $path, you may pass the
     *                                    default value of a child here.  You
     *                                    may leave this null if your $subject
     *                                    is either an array or a stdClass
     *                                    object as these are auto-detected.
     *                                    If you have a class that does not use
     *                                    constructor arguments, then you may
     *                                    also leave this set to null.
     *                                    Finally, if $subject is a class that
     *                                    needs constructor arguments, then you
     *                                    can pass a template object, which
     *                                    will be cloned.
     *
     * @return $this|\AKlump\Data\Data
     */
    public function set(&$subject, $path, $value, $childTemplate = null);
}

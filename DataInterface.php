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
     * @param mixed        $defaultValue Defaults to null.
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException If the path is invalid.
     */
    public function get($subject, $path, $defaultValue = null);
}

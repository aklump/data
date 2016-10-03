<?php
namespace AKlump\Data;

interface DataInterface
{
    /**
     * Gets the value at path of subject. If the resolved value is undefined,
     * the defaultValue is returned in its place.
     *
     * @param mixed        $subject
     * @param string|array $path
     * @param mixed        $defaultValue Defaults to null.
     *
     * @return mixed
     */
    public function get($subject, $path, $defaultValue = null);
}

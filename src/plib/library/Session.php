<?php

namespace PleskExt\Welcome;

class Session
{
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($name, $defaultValue = null)
    {
        if (!isset($_SESSION[$name])) {
            return $defaultValue;
        }

        return $_SESSION[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
}

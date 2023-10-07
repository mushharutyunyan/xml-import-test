<?php

namespace Server;

class Autoloader
{
    public function __construct(string $prefix, string $dir)
    {
        $this->autoload($prefix, $dir);
    }

    private function autoload($prefix, $dir): void
    {
        spl_autoload_register(function ($class) use ($prefix, $dir) {
            $base_dir = __DIR__ . $dir;
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
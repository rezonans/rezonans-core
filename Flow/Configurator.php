<?php declare(strict_types=1);

namespace Rezonans\Core\Flow;

use Closure;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Thy guy who read .env, provide the access to environment variables, process config scripts
 */
class Configurator
{
    /** @var Dotenv */
    protected $dotEnv;

    /**
     * DI
     * @param Dotenv $dotEnv
     */
    public function __construct(Dotenv $dotEnv)
    {
        $this->dotEnv = $dotEnv;
    }

    /**
     * Public environment from .env file
     * @param string $path
     * @return Configurator
     */
    public function loadEnv(string $path): Configurator
    {
        if (is_readable($path)) {
            $this->dotEnv->load($path);
        }

        return $this;
    }

    /**
     * Include all php scripts in the dir
     * @param string $path
     * @return Configurator
     */
    public function readConfigDir(string $path): Configurator
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException(
                sprintf("Config dir isn't a directory! %s given.", $path));
        }

        $entitiesDir = new \DirectoryIterator($path);
        foreach ($entitiesDir as $fileInfo) {
            if (!$fileInfo->isFile() || ('php' !== $fileInfo->getExtension())) {
                continue;
            }
            require_once($fileInfo->getPathname());
        }

        return $this;
    }

    /**
     * Accessor to environment vars
     * @param string $var
     * @param $default
     * @return null|mixed
     */
    public function env(string $var, $default = null)
    {
        $value = getenv($var) ?? null;

        if ($value === false) {
            return $default instanceof Closure ? $default() : $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (
            strlen($value) > 1
            && (0 === strpos($value, '"'))
            && (strlen($value) - 1 === strpos($value, '"'))
        ) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
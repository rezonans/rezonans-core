<?php declare(strict_types=1);

namespace Rezonans\Core\Flow;

use Rezonans\Core\Facades\Core;
use Rezonans\Core\Wordpress\WP;
use Rezonans\Core\Wordpress\WpProvider;

/**
 * Provide methods to get all important pathes in the application
 */
class Path
{
    /**
     * @var string
     */
    protected $root;

    /**
     * @var string
     */
    protected $corePath;

    /** @var WpProvider */
    protected $wpProvider;

    /**
     * Path constructor.
     * @param string $corePath
     * @throws \Error
     * @throws \Exception
     */
    public function __construct(string $corePath)
    {
        $this->wpProvider = Core::get(WpProvider::class);

        if(!defined('REZONANS_ROOT')) {
            throw new \Exception("REZONANS_ROOT not defined!");
        }

        if(!is_dir(REZONANS_ROOT)) {
            throw new \Exception("Invalid root dir");
        }
        $this->root = REZONANS_ROOT;

        if(empty($corePath) || !is_dir($corePath)) {
            throw new \Exception("Invalid core dir");
        }
        $this->corePath = $corePath;
    }

    /**
     * Get absolute project directory path
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getProjectRoot(string $tail = ''): string
    {
        if(empty($this->root) || !is_dir($this->root)) {
            throw new \Exception("Invalid root dir or not set yet!");
        }

        return realpath($this->root) . $tail;
    }

    /**
     * Get absolute path to config folder
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getConfigPath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/config' . $tail;
    }

    /**
     * Get absolute path to wordpress folder
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getWpPath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/wordpress' . $tail;
    }

    /**
     * Get absolute path to Core folder
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getCorePath(string $tail = ''): string
    {
        return $this->corePath;
    }

    /**
     * Get absolute path to Application folder
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getAppPath(string $tail = ''): string
    {
        return $this->getSrcPath() . '/app' . $tail;
    }

    /**
     * Get absolute path to Application templates folder
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getTplPath(string $tail = ''): string
    {
        return $this->getSrcPath() . '/app/templates' . $tail;
    }

    /**
     * Get absolute path to source folder
     * @param string $tail
     * @return string
     * @throws \Exception
     */
    public function getSrcPath(string $tail = ''): string
    {
        return $this->getProjectRoot() . '/src' . $tail;
    }

    /**
     * Get current url with WP global query
     * @return string
     * @throws \Exception
     * @throws \Error
     */
    public function getCurrentUrl(): string
    {
        /** @var WP $wp */
        $wp = Core::get(WP::class);
        return $this->wpProvider->homeUrl(
            $this->wpProvider->addQueryArg([], $wp->request));
    }

    /**
     * Get WP theme directory URI
     * @param string $tail
     * @return string
     * @throws \ErrorException
     */
    public function getWpThemeUri(string $tail = ''): string
    {
        return $this->wpProvider->getTemplateDirectoryUri() . $tail;
    }

    /**
     * Get public css folder URI
     * @param string $tail
     * @return string
     * @throws \ErrorException
     */
    public function getCssUri(string $tail = ''): string
    {
        return $this->getWpThemeUri('/css' . $tail);
    }

    /**
     * Get public js folder URI
     * @param string $tail
     * @return string
     * @throws \ErrorException
     */
    public function getJsUri(string $tail = ''): string
    {
        return $this->getWpThemeUri('/js' . $tail);
    }

    /**
     * Get public images folder URI
     * @param string $tail
     * @return string
     * @throws \ErrorException
     */
    public function getImagesUri(string $tail = ''): string
    {
        return $this->getWpThemeUri('/images' . $tail);
    }

    /**
     * Get public fonts folder URI
     * @param string $tail
     * @return string
     * @throws \ErrorException
     */
    public function getFontsUri(string $tail = ''): string
    {
        return $this->getWpThemeUri('/fonts' . $tail);
    }
}
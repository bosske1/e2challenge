<?php
/**
 * Created by PhpStorm.
 * User: bosske1
 * Date: 23.12.18
 * Time: 03:54
 */

namespace Widget\Models;


class Competition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $globalImportance;

    /**
     * @var string
     */
    protected $url;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Competition
     */
    public function setName(string $name): Competition
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getGlobalImportance(): int
    {
        return $this->globalImportance;
    }

    /**
     * @param int $globalImportance
     * @return Competition
     */
    public function setGlobalImportance(int $globalImportance): Competition
    {
        $this->globalImportance = $globalImportance;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Competition
     */
    public function setUrl(string $url): Competition
    {
        $this->url = $url;
        return $this;
    }
}
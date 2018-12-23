<?php

namespace Widget\Models;

/**
 * Class Team
 * @package Widget\Models
 */
class Team
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var string
     */
    protected $teamUrl;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param mixed $shortName
     * @return Team
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     * @return Team
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTeamUrl()
    {
        return $this->teamUrl;
    }

    /**
     * @param mixed $teamUrl
     * @return Team
     */
    public function setTeamUrl($teamUrl)
    {
        $this->teamUrl = $teamUrl;
        return $this;
    }
}
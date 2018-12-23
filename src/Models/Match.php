<?php

namespace Widget\Models;

/**
 * Class Match
 * @package Widget\Models
 */
class Match
{
    /**
     * @var \DateTime
     */
    protected $matchDate;

    /**
     * @var Team
     */
    protected $homeTeam;

    /**
     * @var Team
     */
    protected $awayTeam;

    /**
     * @var Competition
     */
    protected $competition;

    /**
     * @var string
     */
    protected $url;

    /**
     * @return \DateTime
     */
    public function getMatchDate(): \DateTime
    {
        return $this->matchDate;
    }

    /**
     * @param \DateTime $matchDate
     * @return Match
     */
    public function setMatchDate(\DateTime $matchDate): Match
    {
        $this->matchDate = $matchDate;
        return $this;
    }

    /**
     * @return Team
     */
    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    /**
     * @param Team $homeTeam
     * @return Match
     */
    public function setHomeTeam(Team $homeTeam): Match
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @return Team
     */
    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    /**
     * @param Team $awayTeam
     * @return Match
     */
    public function setAwayTeam(Team $awayTeam): Match
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @return Competition
     */
    public function getCompetition(): Competition
    {
        return $this->competition;
    }

    /**
     * @param Competition $competition
     * @return Match
     */
    public function setCompetition(Competition $competition): Match
    {
        $this->competition = $competition;
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
     * @return Match
     */
    public function setUrl(string $url): Match
    {
        $this->url = $url;
        return $this;
    }
}
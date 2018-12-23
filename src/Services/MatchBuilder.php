<?php

namespace Widget\Services;

use Widget\Models\Competition;
use Widget\Models\Match;
use Widget\Models\Team;

/**
 * Class MatchBuilder
 * @package Widget\Services
 */
class MatchBuilder
{
    const DATA_KEY_MATCHDATE = 'matchdate';
    const DATA_KEY_TEAMS = 'teams';
    const DATA_KEY_HOME = 'home';
    const DATA_KEY_AWAY = 'away';
    const DATA_KEY_TEAM_FULLNAME = 'fullname';
    const DATA_KEY_TEAM_SHORTNAME = 'shortname';
    const DATA_KEY_TEAM_IMAGEURL = 'image20';
    const DATA_KEY_COMPETITION = 'competition';
    const DATA_KEY_COMPETITION_NAME = 'name';
    const DATA_KEY_COMPETITION_IMPORTANCE = 'globalImportance';

    public function buildMatch(string $matchUrl, array $embeddedData): Match
    {
        $matchData = $embeddedData[$matchUrl];

        $match = new Match();
        $match->setUrl($matchUrl);

        $this->extractMatchData($matchData, $match);

        $match->setCompetition(
            $this->extractCompetition($matchData, $embeddedData)
        );

        $match->setHomeTeam(
            $this->extractTeamData($matchData, $embeddedData, self::DATA_KEY_HOME)
        );

        $match->setAwayTeam(
            $this->extractTeamData($matchData, $embeddedData, self::DATA_KEY_AWAY)
        );

        return $match;
    }

    protected function extractMatchData(array $matchData, Match $match): void
    {
        $matchDate = new \DateTime($matchData[self::DATA_KEY_MATCHDATE]);
        $matchDate->setTimezone(new \DateTimeZone('Europe/Vienna'));
        $match->setMatchDate($matchDate);
    }

    protected function extractTeamData(array $matchData, array $embeddedData, string $teamType): Team
    {
        $matchTeamData = $matchData[self::DATA_KEY_TEAMS];

        $teamData = $embeddedData[$matchTeamData[$teamType]];
        $team = new Team();
        $team->setName($teamData[self::DATA_KEY_TEAM_FULLNAME]);
        $team->setShortName($teamData[self::DATA_KEY_TEAM_SHORTNAME]);
        $team->setImageUrl($teamData[self::DATA_KEY_TEAM_IMAGEURL]);
        $team->setTeamUrl($matchTeamData[$teamType]);

        return $team;
    }

    protected function extractCompetition(array $matchData, array $embeddedData): Competition
    {
        $competitionData = $embeddedData[$matchData['competition']];

        $competition = new Competition();
        $competition->setUrl($matchData['competition']);
        $competition->setName($competitionData['name']);
        $competition->setGlobalImportance($competitionData['globalImportance']);

        return $competition;
    }
}
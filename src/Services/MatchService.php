<?php

namespace Widget\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Widget\Models\Match;

/**
 * Class MatchService
 * @package Widget\Services
 */
class MatchService
{
    const DATA_KEY_PAGINATION = 'pagination';
    const DATA_KEY_PAGINATION_NEXT = 'next';
    const DATA_KEY_ATTACHMENTS = 'attachments';
    const DATA_KEY_DATA = 'data';

    /**
     * @var string
     */
    protected $url;
    /**
     * @var Client
     */
    protected $guzzle;

    /**
     * @var MatchBuilder
     */
    protected $matchBuilder;

    /**
     * @var array
     */
    protected $loadedMatchResponses = [];

    /**
     * @var array
     */
    protected $parsedMatches = [];

    /**
     * @var array
     */
    protected $sortedMatches = [];

    /**
     * MatchService constructor.
     * @param Client $guzzle
     * @param string $url
     */
    public function __construct(Client $guzzle, MatchBuilder $matchBuilder, string $url)
    {
        $this->guzzle = $guzzle;
        $this->matchBuilder = $matchBuilder;
        $this->url = $url;
    }

    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return MatchService
     */
    public function firstLoadMatches(\DateTime $dateFrom, \DateTime $dateTo): MatchService
    {
        $matchesUrl = $this->buildUrl($dateFrom, $dateTo);
        $matchesPage = $this->guzzle->get($matchesUrl);

        $matchesResponse = $this->handleMatchesResponse($matchesPage);

        while($this->hasNextPage($matchesResponse)) {
            $matchesPage = $this->guzzle->get($this->url . $matchesResponse[self::DATA_KEY_PAGINATION][self::DATA_KEY_PAGINATION_NEXT]);
            $matchesResponse = $this->handleMatchesResponse($matchesPage);
        }

        // Now let's process some data:
        foreach ($this->loadedMatchResponses as $loadedMatchResponse) {
            if (isset($loadedMatchResponse[self::DATA_KEY_DATA]) && is_array($loadedMatchResponse[self::DATA_KEY_DATA])) {
                foreach ($loadedMatchResponse[self::DATA_KEY_DATA] as $loadedMatch) {
                    $this->parsedMatches[] = $this->matchBuilder->buildMatch($loadedMatch, $loadedMatchResponse[self::DATA_KEY_ATTACHMENTS]);
                }
            }
        }

        return $this;
    }

    /**
     * @return MatchService
     */
    public function thenSortMatches(): MatchService
    {
        /**
         * @var Match $parsedMatch
         */
        foreach ($this->parsedMatches as $parsedMatch) {
            $this->sortedMatches[$parsedMatch->getMatchDate()->format('Y-m-d')]['matchDate'] = $parsedMatch->getMatchDate()->format('d.m.Y');
            $this->sortedMatches[$parsedMatch->getMatchDate()->format('Y-m-d')]['data'][] = $parsedMatch;
        }

        foreach ($this->sortedMatches as $date => &$sortedMatchesData) {
            uasort($sortedMatchesData['data'],
                function (Match $a, Match $b) {
                    return ($a->getCompetition()->getGlobalImportance() > $b->getCompetition()->getGlobalImportance()) ? -1 :
                        ($a->getCompetition()->getGlobalImportance() === $b->getCompetition()->getGlobalImportance() ? $a->getMatchDate() > $b->getMatchDate() : 1);
                }
            );

            $sortedMatchesData['data'] = array_slice($sortedMatchesData['data'], 0, 15);
        }

        return $this;
    }

    public function finallyReturnSortedMatches(): array
    {
        return $this->sortedMatches;
    }

    /**
     * @param Response $response
     * @return array|null
     */
    protected function handleMatchesResponse(Response $response): ?array
    {
        $matchesResponse = null;
        if ($response && $response->getStatusCode() === 200) {
            $matchesResponse = json_decode($response->getBody(), true);
            $this->loadedMatchResponses[] = $matchesResponse;
        }

        return $matchesResponse;
    }

    /**
     * @param array|null $matchesResponse
     * @return bool
     */
    protected function hasNextPage(?array $matchesResponse): bool
    {
        return $matchesResponse && isset($matchesResponse[self::DATA_KEY_PAGINATION])
            && isset($matchesResponse[self::DATA_KEY_PAGINATION][self::DATA_KEY_PAGINATION_NEXT]);
    }

    /**
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @return string
     */
    protected function buildUrl(\DateTime $dateFrom, \DateTime $dateTo): string
    {
        return <<<URL
{$this->url}/v3/de_DE/15/matches?attach=matches.competition&states=PRE&matchdate_from={$dateFrom->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z')}&matchdate_to={$dateTo->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z')}
URL;

    }
}
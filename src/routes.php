<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/matches', function (Request $request, Response $response, array $args) {
    $dateFrom = new \DateTime();
    $dateTo = (new \DateTime())->modify('+3 day');

    $matches = $this->matchService->firstLoadMatches($dateFrom, $dateTo)->thenSortMatches()->finallyReturnSortedMatches();

    $guzzle = $this->guzzle;
    $matchesUrl = $this->settings['matchesUrl'];

    // Render index view
    return $this->renderer->render(
        $response,
        'index.phtml',
        [
            'matchDates' => $matches,
            'urlBase' => $matchesUrl,
            'imageRenderer' => function(\Widget\Models\Team $team) use ($guzzle, $matchesUrl) {
                $image = "";
                try {
                    $imageDataRaw = $guzzle->get($matchesUrl . $team->getImageUrl());
                    $imageData = json_decode($imageDataRaw->getBody(), true);
                    $image = "data:" . $imageData['data']['mime'] . ";base64," . $imageData['data']['src'];
                } catch (\GuzzleHttp\Exception\ClientException $clientException) {
                    // handle or something:
                }

                return $image;
            }
        ]
    );
});

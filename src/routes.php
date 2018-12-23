<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/matches', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

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
            'matches' => $matches,
            'urlBase' => $matchesUrl,
            'imageRenderer' => function(\Widget\Models\Team $team) use ($guzzle, $matchesUrl) {
                $imageHtml = "";
                try {
                    $imageDataRaw = $guzzle->get($matchesUrl . $team->getImageUrl());
                    $imageData = json_decode($imageDataRaw->getBody(), true);
                    $imageHtml = "<img src='data:" . $imageData['data']['mime'] . ";base64," . $imageData['data']['src'] . "' />";
                } catch (\GuzzleHttp\Exception\ClientException $clientException) {
                    // handle or something:
                }

                return $imageHtml;
            }
        ]
    );
});

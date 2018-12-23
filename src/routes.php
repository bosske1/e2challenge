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

    // Render index view
    return $this->renderer->render($response, 'index.phtml', ['matches' => $matches]);
});

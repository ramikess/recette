<?php

declare(strict_types=1);

namespace App\Service\Nutrition\ApiNutritionix;

use App\Enum\NutritionAnalysisType;
use App\Service\Nutrition\NutritionProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class NutritionixClient implements NutritionProviderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $nutritionixAppId,
        private string $nutritionixAppKey,
        private string $nutritionixApiProtocol,
        private string $nutritionixApiHost,
        private string $nutritionixApiVersion,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function analyze(array $query, NutritionAnalysisType $nutritionAnalysisType): array
    {
        try {
            $response = $this->httpClient->request(Request::METHOD_POST, $this->buildUrl($nutritionAnalysisType), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-app-id' => $this->nutritionixAppId,
                    'x-app-key' => $this->nutritionixAppKey,
                ],
                'json' => [
                    'query' => implode(' \n ', $query),
                ],
            ]);
        } catch (\Throwable $exception) {
            $this->logger->critical(
                sprintf('Nutritionix API call failed: %s', $exception->getMessage()));

            throw new \RuntimeException(sprintf('A problem has occurred while calling Nutritionix API, error: %s', $exception->getMessage()));
        }

        return isset($response->toArray()['foods']) ? $response->toArray()['foods'] : [];
    }

    private function buildUrl(NutritionAnalysisType $analysisType): string
    {
        return sprintf(
            '%s://%s/%s%s',
            $this->nutritionixApiProtocol,
            $this->nutritionixApiHost,
            $this->nutritionixApiVersion,
            $analysisType->endpoint()
        );
    }
}

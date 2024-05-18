<?php

namespace App\Services\ApiService;

use App\Services\ApiService\DTO\ApiRequestData;
use App\Services\ApiService\Exceptions\ApiConnectionException;
use App\Services\ApiService\Exceptions\ApiRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class GuzzleService implements ApiServiceInterface
{
    private const BASE_TIMEOUT = 5;

    public function makeRequest(ApiRequestData $dto)
    {
        $client = new Client([
            'timeout' => self::BASE_TIMEOUT
        ]);

        $bodyType = 'form_params';

        try {
            $response = $client->request($dto->method->value, $dto->url, [
                'query' => $dto->queryParams,
                $bodyType => $dto->payload,
                'headers' => $dto->headers
            ]);

            $responseContent = $response->getBody()->getContents();
            $data = $this->decode($responseContent);

            return $data;
        } catch (RequestException $e) {
            throw new ApiRequestException('Error occurred during the request');
        } catch (ConnectException $e) {
            throw new ApiConnectionException('Network error or connection timeout');
        } catch (GuzzleException $e) {
            throw new ApiRequestException('An unexpected error occurred');
        }
    }

    private function decode($response)
    {
        return json_decode($response, true);
    }
}

<?php

namespace App\Domain\Simkl;

use Carbon\CarbonInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SimklAPI
{
    private const ENDPOINT = 'https://api.simkl.com/';

    private string $accessToken = '';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
    ){

    }

    public function start(string $redirectURL): string
    {
        return url()->query('https://simkl.com/oauth/authorize',
            [
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'redirect_uri' => $redirectURL,
            ]);
    }

    public function token(string $code): void
    {
        $response = Http::withHeader('Accept', 'application/json')
            ->post(self::ENDPOINT . '/oauth/token',
                [
                    'code' => $code,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'redirect_uri' => url('sync'),
                    'grant_type' => 'authorization_code'
                ]);
        if (!$response->ok()) {
            throw new \RuntimeException(sprintf("Unexpected status code %s\n\n%s", $response->getStatusCode(), (string)$response->getBody()));
        }

        $this->accessToken = $response->json('access_token');
    }

    public function watchList(string $type, CarbonInterface $date): array
    {
        if (!in_array($type, ['shows', 'movies', 'anime'])) {
            throw new \RuntimeException(sprintf('Unexpected type %s', $type));
        }
        $response = $this->authenticatedRequest()->get(sprintf("%s/sync/all-items/%s/%s", self::ENDPOINT, $type, $type === 'shows' ? 'watching' : 'completed'),
            [
                'date_from' => $date->toISOString(),
            ]);

        if (!$response->ok()) {
            throw new \RuntimeException(sprintf("Unexpected status code %s\n\n%s", $response->getStatusCode(), (string)$response->getBody()));
        }
        return $response->json($type);
    }

    private function authenticatedRequest(): PendingRequest
    {
        if (!$this->accessToken) {
            throw new \RuntimeException('No access token');
        }
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => sprintf("Bearer %s", $this->accessToken),
            'simkl-api-key' => $this->clientId,
        ]);
    }
}

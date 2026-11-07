<?php

namespace App\Services\Dnd;

use Illuminate\Support\Facades\Http;

class DndApiClient
{
    protected $client;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.dnd.base_url'))
            ->acceptJson()
            ->timeout(30)
            ->retry(3, 500);

    }

    public function get(string $endpoint): array
    {
        return $this->client->get($endpoint)->throw()->json();
    }

    public function getAll(string $endpoint): array
    {
        $response = $this->get($endpoint);

        return $response['results'] ?? [];
    }
}

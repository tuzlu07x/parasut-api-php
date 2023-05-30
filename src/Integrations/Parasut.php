<?php

namespace Integrations;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Cache\CacheItemInterface;

class Parasut
{
    private ?Client $client = null;
    private CacheItemInterface $cache;
    public ?int $companyId;
    protected string $userName;
    protected string $password;
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUrl;
    protected ?string $accessToken = null;
    protected string $baseUri = 'https://api.parasut.com';
    protected array $tokens = [];
    protected ?string $refreshToken = null;
    private bool $isLoggedIn = false;

    public function __construct(CacheItemInterface $cache, string $userName, string $password, string $companyId, string $clientId, string $clientSecret, string $redirectUrl)
    {
        $this->cache = $cache;
        $this->userName = $userName;
        $this->password = $password;
        $this->companyId = $companyId;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl = $redirectUrl;
    }

    public function client(): Client
    {
        if ($this->client === null) {
            $this->client = new Client([
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],

            ]);
        }

        return $this->client;
    }

    public function login(): void
    {
        $this->tokens = $this->cache->get('parasut_tokens', function () {
            try {
                $response = $this->client()->post('/oauth/token', [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'username' => $this->userName,
                        'password' => $this->password,
                        'redirect_uri' => $this->redirectUrl,
                    ],
                ]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (RequestException $e) {
                return json_decode($e->getResponse()->getBody()->getContents());
            }
        });
        $this->isLoggedIn = true;
    }

    public function refreshToken(): ?array
    {
        try {
            $response = $this->client()->post('/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $this->tokens['refresh_token'],
                ],
            ]);
            $this->tokens = json_decode($response->getBody()->getContents(), true);
            return $this->tokens;
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
        $this->isLoggedIn = true;
    }

    public function request(string $method, string $url, array $data = [])
    {
        if ($this->isLoggedIn === false) {
            $this->login();
        }

        try {
            $response = $this->client()->request($method, $url, [
                ($method === 'GET' ? 'query' : 'form_params') => $data,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->tokens['access_token'],
                ],
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {

            if ($e->getResponse()->getStatusCode() === 401) {
                $this->tokens = $this->refreshToken();
                return $this->request($method, $url, $data);
            }

            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function get(string $url, array $data = [])
    {
        return $this->request('GET', $url, $data);
    }

    public function post(string $url, array $data = [])
    {
        return $this->request('POST', $url, $data);
    }

    public function put(string $url, array $data = [])
    {
        return $this->request('PUT', $url, $data);
    }

    public function delete(string $url, array $data = [])
    {
        return $this->request('DELETE', $url, $data);
    }

    public function products(): Product
    {
        return new Product($this);
    }

    public function saleInvoice(): SaleInvoice
    {
        return new SaleInvoice($this);
    }

    public function stockMovement(): StockMovement
    {
        return new StockMovement($this);
    }

    public function warehouse(): Warehouse
    {
        return new Warehouse($this);
    }

    public function onCache($cacheModel)
    {
        return $cacheModel;
    }
}

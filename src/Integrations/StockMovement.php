<?php

namespace Parasut\Ftuzlu\Integrations;

use GuzzleHttp\Exception\RequestException;

class StockMovement
{
    public function __construct(public Parasut $parasut)
    {
    }

    public function index(): array
    {
        $data = [];
        $page = 1;
        try {
            do {
                $response = $this->parasut->get('v4/' . $this->parasut->companyId . '/stock_movements?page[number]=' . $page);
                $data = array_merge($data, $response['data'] ?? []);
                $page++;
            } while ($response['meta']['current_page'] < $response['meta']['total_pages']);
            return $data;
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }
}

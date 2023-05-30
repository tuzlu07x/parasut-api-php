<?php

namespace Parasut\Ftuzlu\Integrations;

use GuzzleHttp\Exception\RequestException;

class Category
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
                $response = $this->parasut->get('v4/' . $this->parasut->companyId . '/item_categories?page[number]=' . $page);
                $data = array_merge($data, $response['data'] ?? []);
                $page++;
            } while ($response['meta']['current_page'] < $response['meta']['total_pages']);
            return $data;
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->parasut->post('v4/' . $this->parasut->companyId . '/item_categories', [
                'data' => [
                    'type' => 'item_categories',
                    'attributes' => $data['attributes'],
                ]
            ]);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function show(int $id): ?string
    {
        try {
            return json_encode($this->parasut->get('v4/' . $this->parasut->companyId . '/item_categories/' . $id));
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function update(int $id, array $data): ?string
    {
        try {
            return json_encode($this->parasut->put('v4/' . $this->parasut->companyId . '/item_categories/' . $id, [
                'data' => [
                    'type' => 'item_categories',
                    'id' => $id,
                    'attributes' => $data['attributes'],
                    'relationships' => $data['relationships'],
                ]
            ]));
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function delete(int $id): ?string
    {
        try {
            return $this->parasut->delete('v4/' . $this->parasut->companyId . '/item_categories/' . $id);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }
}

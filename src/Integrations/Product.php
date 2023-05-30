<?php

namespace Integrations;

use GuzzleHttp\Exception\RequestException;

class Product
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
                $response = $this->parasut->get('v4/' . $this->parasut->companyId . '/products?page[number]=' . $page);
                $data = array_merge($data, $response->data ?? []);
                $page++;
            } while ($response->meta->current_page < $response->meta->total_pages);
            return $data;
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function create(array $data)
    {
        try {
            return $this->parasut->post('v4/' . $this->parasut->companyId . '/products', [
                'data' => [
                    'type' => 'products',
                    'attributes' => $data['attributes'],
                    'relationships' => $data['relationships'],
                    'category' => $data['category'],
                ]
            ]);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function show(int $id): ?string
    {
        try {
            return $this->parasut->get($this->parasut->companyId . '/products/' . $id);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function update(int $id, array $data): ?string
    {
        try {
            return $this->parasut->put('v4/' . $this->parasut->companyId . '/products/' . $id, [
                'data' => [
                    'type' => 'products',
                    'id' => $id,
                    'attributes' => $data['attributes'],
                    'relationships' => $data['relationships'],
                    'category' => $data['category'],
                ]
            ]);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function delete(int $id): ?string
    {
        try {
            return $this->parasut->delete('v4' . $this->parasut->companyId . '/products/' . $id);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }
}

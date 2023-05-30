<?php

namespace Integrations;

use GuzzleHttp\Exception\RequestException;

class SaleInvoice
{

    public function __construct(public Parasut $parasut)
    {
    }
    public function index()
    {
        $data = [];
        $page = 1;
        try {
            do {
                $response = $this->parasut->get('v4/' . $this->parasut->companyId . '/sales_invoices?page[number]=' . $page);
                $data = array_merge($data, $response->data ?? []);
                $page++;
            } while ($response['meta']['current_page'] < $response['meta']['total_pages']);
            return $data;
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function show(int $id)
    {
        return $this->parasut->get('v4/' . $this->parasut->companyId . '/sales_invoices/' . $id);
    }

    public function create(array $data)
    {
        return $this->parasut->get('v4/' . $this->parasut->companyId . '/sales_invoices', [
            'data' => [
                'type' => 'sales_invoices',
                'attributes' => $data['attributes'],
                'relationships' => $data['relationships'],
            ]
        ]);
    }

    public function update(int $id, array $data)
    {
        return $this->parasut->put('v4/' . $this->parasut->companyId . '/sales_invoices/' . $id, [
            'data' => [
                'id' => $id,
                'type' => 'sales_invoices',
                'attributes' => $data['attributes'],
                'relationships' => $data['relationships'],
            ]
        ]);
    }

    public function delete(int $id)
    {
        return $this->parasut->delete('v4/' . $this->parasut->companyId . '/sales_invoices/' . $id);
    }
}

<h3 align="center">PARASUT PHP PACKAGE<br></h3>
<p> Bu Pakette sadece Category, Product, Sale Invoice, Stock Movement ve Warehouse endpointlerini bulunmaktadir. 
</p>

<p> Katkida bulunmak isterseniz mergerequest acabilirsiniz.</p>

## Usage

```
composer require ftuzlu/parasut
```

## Parasut API Auth informations

```php
<?php

        $cache = 'Cache remember or another cache packages';
        $userName = 'ftuzlu'
        $password='*****';
        $companyId='****';
        $clientId='****';
        $clientSecret='****';
        $redirectUrl='https://github.com/tuzlu07x';

$parasut = new Parasut($cache, $userName,$password,$companyId,$clientId,$clientSecret,$redirectUrl)
```

## Category

```php
<?php

$category = new Category($parasut)

$data=[
    "id"=> "string",
    "type"=> "item_categories",
    'attributes' =>[
        "name"=> "string",
        "bg_color"=> "string",
        "text_color"=> "string",
        "category_type"=> "Product",
        "parent_id"=> 0
    ],
];

$list = $category->index();
$create = $category->create($data);
$show = $category->show($id);
$update = $category->update($id, $data);
$delete = $category->delete($id);
```

## Product

```php
<?php

$product = new Product($parasut)
$data=[
    "id"=> "string",
    "type"=> "products",
    'attributes' =>[
       "code": "string",
        "name": "string",
        "vat_rate": 0,
        "sales_excise_duty": 0,
        "sales_excise_duty_type": "string",
        "purchase_excise_duty": 0,
        "purchase_excise_duty_type": "string",
        "unit": "string",
        "communications_tax_rate": 0,
        "archived": true,
        "list_price": 0,
        "currency": "string",
        "buying_price": 0,
        "buying_currency": "string",
        "inventory_tracking": true,
        "initial_stock_count": 0,
        "gtip": "string",
        "barcode": "string"
    ],
];

$list = $product->index();
$create = $product->create($data);
$show = $product->show($id);
$update = $product->update($id, $data);
$delete = $product->delete($id);

```

## Sale Invoice

```php
<?php

$invoice = new SaleInvoice($parasut)
$data=[
    "id"=> "string",
    "type"=> "sales_invoices",
    'attributes' =>[
        "item_type"=> "invoice",
        "description"=> "string",
        "issue_date"=> "2023-05-30",
        "due_date"=> "2023-05-30",
        "invoice_series"=> "string",
        "invoice_id"=> 0,
        "currency"=> "TRL",
        "exchange_rate"=> 0,
        "withholding_rate"=> 0,
        "invoice_discount_type"=> "percentage",
        "invoice_discount"=> 0,
        "billing_address"=> "string",
        "billing_postal_code"=> "string",
        "billing_phone"=> "string",
        "billing_fax"=> "string",
        "tax_office"=> "string",
        "tax_number"=> "string",
        "country"=> "string",
        "city"=> "string",
        "district"=> "string",
        "is_abroad"=> true,
        "order_no"=>"string",
        "order_date"=> "2023-05-30",
        "shipment_addres"=> "string",
        "shipment_included"=> true,
        "cash_sale"=> true,
        "payer_tax_numbers"=> [],
        "payment_account_id"=> 0,
        "payment_date"=> "string",
        "payment_description"=> "string"
    ],
];

$list = $invoce->index();
$create = $invoce->create($data);
$show = $invoce->show($id);
$update = $invoce->update($id, $data);
$delete = $invoce->delete($id);

```

## StockMovement

```php
<?php

    $stock = new StockMovement($parasut)

    $list = $stock->index();

```

## WareHouse

```php
<?php
    $wareHouse = new WareHouse($parasut)

    $data = [
        "id"=> "string",
        "type"=> "warehouses",
        "attributes"=>[
            "name"=> "string",
            "address"=> "string",
            "city"=> "string",
            "district"=> "string",
            "is_abroad"=> true,
            "archived"=> true
        ]
    ];

    $list = $invoce->index();
    $create = $invoce->create($data);
    $show = $invoce->show($id);
    $update = $invoce->update($id, $data);
    $delete = $invoce->delete($id);

```

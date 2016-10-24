<?php
require 'vendor/autoload.php';

use Tykus\DataTransformer\Transformer;
use Tykus\DataTransformer\FormatIdentifier;

# There is no IoC Container in this simple example implementation, so the
# FormatIdentifier dependency must be explicitly instantiated. This would not be
# necessary when we use Laravel's IoC Container to resolve the dependencies.
$transformer = new Transformer('./example.csv', new FormatIdentifier('./example.csv'));


# We can be explicit about how the data gets keyed, transformed and nested by
# defining a closure with the transformation recipe to be applied to each
# row of the data that was imported from the CSV to the Transformer.
$data = $transformer->transform(function($row) {
    return [
        'item_id' => $row['item id'],
        'description' => $row['description'],
        'price' => $row['price'],
        'cost' => $row['cost'],
        'price_type' => $row['price_type'],
        'quantity_on_hand' => $row['quantity_on_hand'],
        'modifiers' => [
            [
                'name' => $row['modifier_1_name'],
                'price' => $row['modifier_1_price']
            ],
            [
                'name' => $row['modifier_2_name'],
                'price' => $row['modifier_2_price']
            ],
            [
                'name' => $row['modifier_3_name'],
                'price' => $row['modifier_3_price']
            ]
        ]
    ];
})->toJson();

header('Content-Type: application/json');
echo $data;

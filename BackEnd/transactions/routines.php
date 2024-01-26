<?php

function updateDB($controller, $products) {
    $hostedImages = [
        "https://freeimage.host/i/JuTPgGp" => "https://iili.io/JuTPgGp.png",
        "https://freeimage.host/i/JuTPr6N" => "https://iili.io/JuTPr6N.png",
        "https://freeimage.host/i/JuTP6FI" => "https://iili.io/JuTP6FI.png",
        "https://freeimage.host/i/JuTPUnR" => "https://iili.io/JuTPUnR.jpg",
        "https://freeimage.host/i/JuTPPat" => "https://iili.io/JuTPPat.jpg",
        "https://freeimage.host/i/JuTPi8X" => "https://iili.io/JuTPi8X.jpg",
        "https://freeimage.host/i/JuTPZwG" => "https://iili.io/JuTPZwG.jpg",
        "https://freeimage.host/i/JuTPmMl" => "https://iili.io/JuTPmMl.png",
        "https://freeimage.host/i/JuTPbn4" => "https://iili.io/JuTPbn4.png",
        "https://freeimage.host/i/JuTPttf" => "https://iili.io/JuTPttf.png",
        "https://freeimage.host/i/JuTi9FS" => "https://iili.io/JuTi9FS.png",
        "https://freeimage.host/i/JuTPpP2" => "https://iili.io/JuTPpP2.png",
        "https://freeimage.host/i/JuTiHc7" => "https://iili.io/JuTiHc7.png"
    ];

    foreach ($products as $product) {
        $images = explode(',', $product['images']);
        $images[0] = $hostedImages[$images[0]];
        $images[1] = $hostedImages[$images[1]];

        $product['images'] = implode(',', $images);
        $controller->updateProduct($product['id'], $product);
    }
}

function updateSTRIPE($stripe, $products_DB) {
    foreach ($products_DB as $product_db) {
        if ($product_db['stripeID'] === null) {
            $images = explode(',', $product_db['images']);
    
            // $product_stripe = \Stripe\Product::create([
            //         'name' => $product_db['name'],
            //         'type' => 'good',
            //         'images' => ["$images[0]", "$images[1]"],
            //     ]);

            $stripe->update(
                $product_db['stripeID'],
                ['images' => $images]
            );
    
            // $price = \Stripe\Price::create([
            //         'product' => $product_stripe->id,
            //         'unit_amount' => $product_db['price'] * 100, // Amount in cents
            //         'currency' => 'usd',
            //     ]);
    
            // $product_db['stripeID'] = $product_stripe->id;
            // $controller->gamesController->updateProduct($product_db['id'], $product_db);
        }
    }
}

require_once "$path/transactions/vendor/autoload.php";
require_once "$path/transactions/secrets.php";

\Stripe\Stripe::setApiKey($stripeSecretKey);

$stripe = new \Stripe\StripeClient($stripeSecretKey);

$gamesController = $controller->gamesController;

updateDB($gamesController, $gamesController->getAllProducts());
updateSTRIPE($stripe, $gamesController->getAllProducts());

// $products_STRIPE = \Stripe\Product::all();

// $products_DB = $controller->gamesController->getAllProducts();

// foreach ($products_DB as $product_db) {
//     if ($product_db['stripeID'] === null) {
//         $images = explode(',', $product_db['images']);

//         $product_stripe = \Stripe\Product::create([
//                 'name' => $product_db['name'],
//                 'type' => 'good',
//                 'images' => ["$baseURL/res/img/$images[0]", "$baseURL/res/img/$images[1]"],
//             ]);

//         $price = \Stripe\Price::create([
//                 'product' => $product_stripe->id,
//                 'unit_amount' => $product_db['price'] * 100, // Amount in cents
//                 'currency' => 'usd',
//             ]);

//         $product_db['stripeID'] = $product_stripe->id;
//         $controller->gamesController->updateProduct($product_db['id'], $product_db);
//     }
// }


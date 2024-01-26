<?php

require_once "$path/transactions/vendor/autoload.php";
require_once "$path/transactions/secrets.php";


\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$stripe = new \Stripe\StripeClient($stripeSecretKey);

$default_price = 'price_1ON4w3Fnn5trez7R937l8w5p';

$line_items = [];
foreach ($_SESSION['cart'] as $stripeID => $quantity) {
  $product = $stripe->products->retrieve($stripeID);
  $priceID = $product->default_price;

  if ($priceID === null) {
    $priceID = $default_price;
  }
  
  $line = ['price' => $priceID, 'quantity' => $quantity];
  
  array_push($line_items, $line);
}


$domain = "$baseURL/transactions/public";

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => [$line_items],
  'mode' => 'payment',
  'success_url' => $domain . '/success.html',
  'cancel_url' => $domain . '/cancel.html',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
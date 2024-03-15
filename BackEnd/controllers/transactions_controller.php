<?php
require_once "$path/controllers/base_controller.php";
require_once "$path/models/transactions_model.php";

class TransactionsController extends BaseController
{
    private $price_id = 'price_1OssePBFJdNNb6QWMRcxmeoY';

    public function __construct($central_controller, $pdo)
    {
        $this->model = new TransactionsModel($pdo);
        $specific_actions = [
            'getLink' => true,
            'create' => true,
            'update' => true,
            'delete' => true
        ];

        parent::__construct($central_controller, $specific_actions);
    }

    public function getLink($data)
    {
        ['donatorID' => $donatorID, 'donateeID' => $donateeID] = $data;

        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_KEY']);

        $link = $stripe->paymentLinks->create([
            'line_items' => [['price' => $this->price_id, 'quantity' => 1]],
            'metadata' => [
                'donatorID' => $donatorID,
                'donateeID' => $donateeID,
            ],
        'success_url' => 'http://localhost:3000/success?session_id={CHECKOUT_SESSION_ID}', // Add your success URL here
        'cancel_url' => 'http://localhost:3000/cancel', // Add your cancel URL here
        ]);

        return $link->url;
    }

    public function getEmbeddedLink($data)
    {
        ['donatorID' => $donatorID, 'donateeID' => $donateeID] = $data;
        
        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_KEY']);

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:4242';

        $checkout_session = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => [[
                'price' => '{{PRICE_ID}}',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'return_url' => $_ENV['JWT_AUDIENCE'] . 'return.html?session_id={CHECKOUT_SESSION_ID}',
        ]);
    }
}

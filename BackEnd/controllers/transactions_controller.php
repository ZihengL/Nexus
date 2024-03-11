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
        ]);

        return $link->url;
    }
}

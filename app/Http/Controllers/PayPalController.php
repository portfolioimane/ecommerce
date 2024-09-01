<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Ensure this is imported correctly
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    private $paypal;

    public function __construct()
    {
        // Initialize PayPal client
        $this->paypal = new PayPalClient;
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
    }

    public function createPayment(Request $request)
    {
        // Create a new order with PayPal
        $paypalOrder = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => 'transaction_test_number' . $request->user()->id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '20.00'
                    ]
                ]
            ],
            'application_context' => [
                'cancel_url' => route('paypal.cancel'),
                'return_url' => route('paypal.success')
            ]
        ]);

        // Check if the order creation was successful
        if (!isset($paypalOrder['id'])) {
            return redirect()->route('paypal.cancel')->with('error', 'Payment order creation failed.');
        }

        // Store order ID in the database
        $order = Order::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['paypal_transaction_id' => $paypalOrder['id'], 'amount' => '20.00']
        );

        // Redirect user to PayPal for payment approval
        return redirect($paypalOrder['links'][1]['href']);
    }

    public function success(Request $request)
    {
        $paymentId = $request->input('token'); // Ensure you're using the correct parameter from the request

        // Ensure the payment ID is not null
        if (!$paymentId) {
            return redirect()->route('paypal.cancel')->with('error', 'Invalid payment ID.');
        }

        // Capture the payment
        $payment = $this->paypal->capturePaymentOrder($paymentId);

        if ($payment['status'] === 'COMPLETED') {
            // Handle successful payment logic
            return view('success');
        } else {
            // Handle failed payment logic
            return view('cancel');
        }
    }

    public function cancel()
    {
        // Handle payment cancellation logic
        return view('cancel');
    }
}

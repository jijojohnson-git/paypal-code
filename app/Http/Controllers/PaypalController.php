<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function processOrder(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setCurrency('USD');

        $data = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('process.success'),
                'cancel_url' => route('process.cancel')
            ]
            ,
            'purchase_units' => [
                0 => [
                    "reference_id" => "test_ref_id1",
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => 1000
                    ]],
                1 => [
                    "reference_id" => "test_ref_id2",
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => 100,
                        'name' => 'kope'
                    ]
                ]
            ]
        ];

        // $request->body = [
        //     "intent" => "CAPTURE",
        //     "purchase_units" => [[
        //         "reference_id" => "test_ref_id1",
        //         "amount" => [
        //             "value" => "100.00",
        //             "currency_code" => "USD"
        //             ]
        //     ]],
        //     "application_context" => [
        //         "cancel_url" => "https://127.0.0.1:8000/cart",
        //         "return_url" => "https://127.0.0.1:8000/"
        //     ]
        // ];

        $response = $provider->createOrder($data);
        // dd($response);

        if(isset($response['id']) && $response['id'] != null)
        {
            foreach($response['links'] as $link)
            {
                if($link['rel'] == 'approve')
                {
                    return redirect()->away($link['href']);
                }
            }
            return redirect('/')->with('error', 'Something went wrong!!!');
        }
            return redirect('/')->with('error', $response['message'] ?? 'Somethings Fishy!');
    }

    public function processSuccess(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);
        // dd($response);

        if(isset($response['status']) && $response['status'] == 'COMPLETED')
        {
            return redirect('/')->with('message', 'Payment Successfull!!!');
        }
        return redirect('/')->with('error', 'Ooops!! Something not right!');
    }

    public function processCancel(Request $request)
    {
        return redirect('/')->with('error', $response['message'] ?? 'You have Cancelled!!!');
    }
}

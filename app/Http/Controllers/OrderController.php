<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = new Order();
        $order->order_number = uniqid('ORD-');
        $order->subtotal = $request->subtotal;

        // Calculate tax using TaxJar API
        $tax = $this->calculateTax($order->subtotal, $request->shipping_address);
        $order->tax = $tax;

        $order->total = $order->subtotal + $order->tax;
        $order->save();

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        return response()->json($order);
    }

    private function calculateTax($subtotal, $address)
    {
        if (env('APP_ENV') == 'production') {
            $url = config('app.tax_jar_production_url');
        } else {
            $url = config('app.tax_jar_sandbox_url');
        }
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('TAXJAR_API_KEY')
        ])->post($url, [
            'from_country' => 'US',
            'from_zip' => '92093',
            'from_state' => 'CA',
            'to_country' => $address['country'],
            'to_zip' => $address['zip'],
            'to_state' => $address['state'],
            'amount' => $subtotal,
            'shipping' => $address['shipping'],
        ]);

        $taxData = $response->json();
        return $taxData['tax']['amount_to_collect'];
    }
}

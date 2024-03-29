<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use Carbon\Carbon;
use App\Components\Helper\ImageProcessing;
use App\Rules\OrderNotProcessedYet;

class OrdersController extends Controller
{
    protected $user = null;

    function __construct() {
        $this->user = Auth::guard('web_api')->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->user->orders();

        Validator::make($request->all(), [
            'status' => 'string|in:processing,completed,cancelled|nullable',
        ])->validate();
        switch ($request->status) {
            case 'completed':
                $orders = $orders->whereNotNull('completion_date');
                break;
            case 'processing':
                $orders = $orders->whereNull('completion_date')->whereNotNull('request_date');
                break;
            case 'cancelled':
                $orders = $orders->whereNull('request_date');
                break;
            default:
                //$orders = $orders->get();
                break;
        };
        $orders = $orders->paginate(5);
        foreach ($orders as $index => $order) {
            $order['order_status'] = $order->getStatus();
            $order['is_reviewed'] = ($order->reviews->count() == $order->orderDetails->count());
            $order['total_amount'] = number_format($order->orderDetails->reduce(function($total,$item) { return $total + $item->product->product_price * $item->quantity;
                                        }),2);
            $order['details_count'] = $order->orderDetails->count();
            $order['details_link'] = route('customer.order.details', ['id' => $order->id]);
            $order['represent'] = $order->orderDetails()->first();
            $order['represent']->product;

        }
        return $orders;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //$cartItems = $this->user->cartItems;
        // if ($cartItems->count() == 0) {
        //     return response()->json([
        //         "message" => "Cart is empty.",
        //         "errors" => [
        //             "cart" => "Cart is empty."
        //         ],
        //     ], 422);
        // }

        Validator::make($request->all(), [
                'cartItems' => 'required|array|min:1',
                'cartItems.*.quantity' => 'required|numeric|min:1',
                'phone' => 'digits:10|nullable',
                'address' => 'required|string|max:255',
                'ward' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'payment' =>  'required',
            ])->validate();

        //dd($request->all());

        if ($request->newAddress == "true") {

            $address = $this->user->addresses()->create([
                'address' => $request->address,
                'ward' => $request->ward,
                'district' => $request->district,
                'province' => $request->province,
                'country' => $request->country,
            ]);
        }

        $address = $request->address.', '.$request->ward.', '.$request->district.', '.$request->province.', '.$request->country;


        $order = $this->user->orders()->create([
            'address' => $address,
            'phone' => empty($request->phone) ? $request->user()->phone : $request->phone,
            'payment' => $request->payment,
            'request_date' => Carbon::now(),
        ]);

        foreach ($request->cartItems as $item) {
            $order->orderDetails()->create([
                'product_id' => $item["product_id"],
                'quantity' => $item["quantity"],
            ]);
        }

        //$this->user->cartItems()->delete();

        return response()
            ->json(['message' => 'Order has been created successfully.',
                    'next' => route('customer.order.details',['id'=> $order->id]) ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Validator::make(['id'=>$id], [
            'id' => ['required', 'exists:orders', new OrderNotProcessedYet ],
        ])->validate();

        $order = $this->user->orders()->findOrFail($id);

        $order->update([
            'request_date' => null,
        ]);

        return response()
            ->json(['message' => 'Order has been cancelled.',
                    'next' => route('customer.order.all') ]);
    }
}

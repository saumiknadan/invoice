<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // view all
    public function get_all_invoice()
    {
        // $invoices = Invoice::all();
        $invoices = Invoice::with('customer')->orderBy('id','DESC')->get();
        return  response()->json([
            'invoices'=>$invoices
        ], 200);
    }

    // search invoice
    public function search_invoice(Request $request)
    {
        $search = $request->get('s');

        if($search!=null){
            $invoices = Invoice::with('customer')
                ->where('id','LIKE',"%$search%")->get();
            return  response()->json([
                'invoices'=>$invoices
            ], 200);
        } else{
            return $this->get_all_invoice();
        }
    }

    public function create_invoices(Request $request)
    {
        $counter = Counter::where('key', 'invoice')->first();
        $random = Counter::where('key', 'invoice')->first();

        $invoice = Invoice::orderBy('id', 'Desc')->first();

        if($invoice){
            $invoice = $invoice->id+1;
            $counters = $counter->value + $invoice;
        }else{
            $counters = $counter->value;
        }

        $formData = [
            'number'=>$counter->prefix.$counters,
            'customer_id'=>null,
            'customer'=>null,
            'date'=>date('Y-m-d'),
            'due_date'=>null,
            'reference'=>null,
            'discount'=>0,
            'term and condition'=>'Default Terms and conditions',
            'items'=> [
                [
                    'product_id'=>null,
                    'product'=>null,
                    'unit_price'=>0,
                    'quantity'=>1
                ]
            ]
            
                ];

                return response()->json($formData);
    }

    public function add_invoice(Request $request)
    {
        $invoiceitem = $request->input("invoice_item");

        $invoicedata['sub_total'] = $request->input('subtotal');
        $invoicedata['total'] = $request->input('total');
        $invoicedata['customer_id'] = $request->input('customer_id');
        $invoicedata['number'] = $request->input('number');
        $invoicedata['date'] = $request->input('date');
        $invoicedata['due_date'] = $request->input('due_date');
        $invoicedata['discount'] = $request->input('discount');
        $invoicedata['reference'] = $request->input('reference');
        $invoicedata['terms_and_conditions'] = $request->input('terms_and_conditions');

        $invoice = Invoice::create($invoicedata);

        foreach(json_decode($invoiceitem) as $item){
             $itemdata['product_id'] = $item->id;
             $itemdata['invoice_id'] = $invoice->id;
             $itemdata['quantity'] = $item->quantity;
             $itemdata['unit_price'] = $item->unit_price;

             InvoiceItem::create($itemdata);
        }


    }

    public function show_invoice($id)
    {
        $invoice= Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json([
            'invoice'=>$invoice
        ],200);
    }

    public function edit_invoice($id)
    {
        $invoice= Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json([
            'invoice'=>$invoice
        ],200);
    }

    public function delete_invoice_items($id){
        $invoiceitem = InvoiceItem::findOrFail($id);
        $invoiceitem->delete();
    }

    public function update_invoice(Request $request, $id){

        $invoice = Invoice::where('id',$id)->first();

        $invoiceitem = $request->input("invoice_item");

        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->discount = $request->discount;
        $invoice->reference = $request->reference;
        $invoice->terms_and_conditions = $request->terms_and_conditions;

        $invoice->update($request->all());
        $invoice->invoice_items()->delete();

        foreach(json_decode($invoiceitem) as $item){

            $itemdata['product_id'] = $item->product_id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemdata);
       }
         
    }
    

    public function delete_invoice($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_items()->delete();
        $invoice->delete();
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

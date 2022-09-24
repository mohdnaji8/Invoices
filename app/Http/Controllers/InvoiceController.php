<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;

class InvoiceController extends Controller
{
    //
    public function get_all_invoice(){
        $invoices = Invoice::with('customer')->orderBy('id','DESC')->get();
        return response()->json([
            'invoices'=>$invoices
        ],200);
    }

    public function search_ivoice(Request $request){
        $search = $request->s;
        if ($search != null)
        {
            $invoices=Invoice::with('customer')
                ->where('id','LiKE',"%$search%")
                ->get();
            return response()->json([
                'invoices'=>$invoices
            ],200);
        }
        else {
            return  $this->get_all_invoice();
        }
    }
    public function create_invoice(Request $request){
        $counter = Counter::where('key','invoice')->first();
        $random = Counter::where('key','invoice')->first();
        $invoice =  Invoice::orderBy('id','DESC')->first();
        if ($invoice){
            $invoice = $invoice->id+1;
            $counters = $counter->value + $invoice;
        }else{
            $counters =$counter->value;
        }
        $formData =[
            'number'=> $counter->prefix.$counters,
            'customer_id'=>null,
            'customer'=>null,
            'date'=>date('Y-m-d'),
            'due_date'=>null,
            'refrence'=>null,
            'discount'=>0,
            'terms_and_conditions'=>'default terms and conditions',
            'items'=>[
                [
                    'product_id'=>null,
                    'product'=>null,
                    'unit_price'=>0,
                    'quantity'=>1,
                ]
            ],
        ];
        return response()->json($formData);
    }
    public function add_invoice(Request $request){
        $invoiceitem = $request->input('invoice_item');

        $invoicedata['sub_total'] =$request->input('subtotal');
        $invoicedata['total'] =$request->input('total');
        $invoicedata['customer_id'] =$request->input('customer_id');
        $invoicedata['number'] =$request->input('number');
        $invoicedata['date'] =$request->input('date');
        $invoicedata['due_date'] =$request->input('due_date');
        $invoicedata['discount'] =$request->input('discount');
        $invoicedata['refrence'] =$request->input('refrence');
        $invoicedata['terms_and_conditions'] =$request->input('terms_and_conditions');

        $invoice = Invoice::create($invoicedata);

        foreach (json_decode($invoiceitem) as $item){
            $itemdata['product_id'] =$item->id;
            $itemdata['invoice_id'] =$invoice->id;
            $itemdata['quantity'] =$item->quantity;
            $itemdata['unit_price'] =$item->unit_price;
            InvoiceItem::create($itemdata);

        }
    }
    public function show_invoice($id){
//        $invoice = Invoice::with('customer')->where('id',$id)->get();
        $invoice = Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json([
            'invoice'=>$invoice
        ],200);
    }
    public function edit_invoice($id){
        $invoice = Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json([
            'invoice'=>$invoice
        ],200);
    }

    public function update_invoice(Request $request, $id)
    {
        $invoice = Invoice::where('id',$id)->first();
        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->discount = $request->discount;
        $invoice->refrence = $request->refrence;
        $invoice->terms_and_conditions = $request->terms_and_conditions;
        $invoice->update($request->all());
        $invoice_items = $request->input('invoice_items');
        $invoice->invoice_items()->delete();
        foreach (json_decode($invoice_items) as $item){

            $itemdata['product_id'] =$item->product_id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] =$item->quantity;
            $itemdata['unit_price'] =$item->unit_price;
            InvoiceItem::create($itemdata);
        }
    }

    public function delet_invoice_item($id){
        $invoice_item = InvoiceItem::findOrFail($id);
        $invoice_item->delete();
    }
    public function delet_invoice($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->invoice_items()->delete();
        $invoice->delete();
    }
}

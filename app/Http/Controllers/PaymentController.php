<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shareholder;
use App\Models\Payment;

class PaymentController extends Controller
{
    

    public function index(Request $request)
        {
            $countryFilter = $request->input('country');
            $statusFilter = $request->input('status');

            $payments = Payment::with(['shareholder' => function ($query) use ($countryFilter) {
                if ($countryFilter) {
                    $query->where('country', $countryFilter);
                            }
                }])
                /* ->when($countryFilter, function ($query, $countryFilter) {
                    return $query->where('shareholders.country', $countryFilter);
                }) */ 
                ->when($statusFilter, function ($query, $statusFilter) {
                    return $query->where('payments.status', $statusFilter);
                })
                ->paginate(25);

            $countries = Shareholder::distinct('country')->pluck('country'); 

            return view('payments', compact('payments', 'countries'));
        }
    public function shareholder_payments($eid)
    {     
        $shareholder = Shareholder::findOrFail(decrypt($eid));

        $payments = $shareholder->payments;

        return view('shareholders.payments', compact('shareholder', 'payments'));
   
    }


    public function store(Request $request, $eid)
    {
        
        $shareholder = Shareholder::findOrFail(decrypt($eid));

        $request->validate([
            'duration' => 'required',
            'annual_amount' => 'required',
            'installment_type' => 'required',
            'start_date' => 'required',
        ]);

        $shareholder->update([
            'duration' => $request->input('duration'),
            'annual_amount' => $request->input('annual_amount'),
            'installment_type' => $request->input('installment_type'),
            'start_date' => $request->input('start_date'),
        ]);

        $dueDates = $request->input('due_date', []);
        $amounts = $request->input('amount_to_pay', []);
        
        $shareholderId = $shareholder->id;

        
        foreach ($dueDates as $k => $dueDate) {
            Payment::create([
                'shareholder_id' => $shareholderId,
                'due_date' => $dueDate,
                'installment_amount' => $amounts[$k],
                'status' => 'Pending'
            ]);
        }

        // return redirect()->route('shareholders.payments',encrypt($shareholder->id))->with('success', 'Shareholder created successfully');
         return response()->json(['redirect_url' => route('shareholders.payments',encrypt($shareholder->id))]);

    }

    public function make_payments($eid)
    {     
        $payment = Payment::findOrFail(decrypt($eid));
        $update=['status' => 'Paid','payment_date'=>date('Y-m-d'),'paid_amount'=>$payment->installment_amount];
        $payment->update($update);

        return response()->json(['message' => 'Payment made successfully','data'=>$update], 200);
   
    }
    
}

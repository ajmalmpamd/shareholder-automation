<?php

namespace App\Http\Controllers;

use App\Models\Shareholder;
use Illuminate\Http\Request;

class ShareholderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Shareholder::latest()->paginate(25);
    
        return view('shareholders.index',compact('rows'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {     
           return view('shareholders.create');
   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:shareholders',
            'mobile' => 'required',
            'country' => 'required',
        ]);

        $shareholder = Shareholder::create($request->all());

        return redirect()->route('shareholders.create-payments',encrypt($shareholder->id))->with('success', 'Shareholder created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shareholder  $shareholder
     * @return \Illuminate\Http\Response
     */
    public function show(Shareholder $shareholder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shareholder  $shareholder
     * @return \Illuminate\Http\Response
     */
    public function edit(Shareholder $shareholder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shareholder  $shareholder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shareholder $shareholder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shareholder  $shareholder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shareholder $shareholder)
    {
        //
    }


    public function create_payments($eid)
    {     
        $shareholder = Shareholder::findOrFail(decrypt($eid));
         if ($shareholder->installment_type){
            return redirect()->route('shareholders.payments',encrypt($shareholder->id));
         }
        return view('shareholders.create_payments', compact('shareholder'));
   
    }
}

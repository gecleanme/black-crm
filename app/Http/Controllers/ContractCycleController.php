<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Termwind\Components\Dd;
class ContractCycleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Contract $contract_id)
    {

//        if(!session('contract_id'))
//            abort(406,'You must Create a contract first before attaching a cycle');

        return Inertia::render('Cycle/Create', [
            'contract_id' => $contract_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
      //  dd($request->all());
        //$latest_contract= session('contract_id');

        $validatedData = $request->validate([
            'value' => 'required',
            'premium' => 'required',
            'notes' => 'nullable',
            'vendor' => 'string',
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d'],
            'contract_id' => 'required'
        ]);

        $validatedData['start_date'] = Carbon::parse( $validatedData['start_date']);
        $validatedData['end_date'] = Carbon::parse( $validatedData['end_date']);

        $cycle = ContractCycle::create($validatedData);


      if ($request->hasFile('attachments')){
          foreach ($request->file('attachments') as $file){
            $attachments = $file->store('attachments', 'public');
              $cycle->attachments()->create([
                  'attachments' => $attachments
              ]);
          }
      }

        return redirect('/contract/edit/'.$validatedData['contract_id'])->with('success','Success Message');







    }


    /**
     * Display the specified resource.
     */
    public function show(ContractCycle $contractCycle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractCycle $contractCycle)
    {
        $contractCycle->load('attachments');
        return Inertia::render('Cycle/Edit', [
            'cycle' => $contractCycle,
            'url' => env('APP_URL')
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractCycle $contractCycle)
    {
        $validatedData = $request->validate([
            'value' => 'required',
            'premium' => 'required',
            'notes' => 'nullable',
            'vendor' => 'string',
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d']
        ]);

        $validatedData['start_date'] = Carbon::parse( $validatedData['start_date']);
        $validatedData['end_date'] = Carbon::parse( $validatedData['end_date']);

        $contractCycle->update($validatedData);


        if ($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file){
                $attachments = $file->store('attachments', 'public');
                $contractCycle->attachments()->create([
                    'attachments' => $attachments
                ]);
            }
        }

            return redirect('/contract/edit/'. $contractCycle->contract_id)->with('success', 'Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractCycle $contractCycle)
    {
        //
    }
}

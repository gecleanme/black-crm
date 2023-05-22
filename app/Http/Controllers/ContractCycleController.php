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
    public function create()
    {
       // dd(ContractCycle::all());
        return Inertia::render('Cycle/Create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        $latest_contract= session('contract_id');

        $validatedData = $request->validate([
            'value' => 'required',
            'premium' => 'required',
            'notes' => 'nullable',
            'vendor' => 'string',
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d']
        ]);

        $startDate = Carbon::parse( $validatedData['start_date']);
        $endDate = Carbon::parse( $validatedData['end_date']);

      $cycle= ContractCycle::create([
            'contract_id' => $latest_contract,
            'value' => $validatedData['value'],
            'premium' => $validatedData['premium'],
            'notes' => $validatedData['notes'],
            'vendor' => $validatedData['vendor'],
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

      if ($request->hasFile('attachments')){
          foreach ($request->file('attachments') as $file){
            $attachments = $file->store('attachments', 'public');
              $cycle->attachments()->create([
                  'attachments' => $attachments
              ]);
          }
      }

        session()->forget('contract_id');


        return redirect('contract/create')->with('success','asasas');




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
            'cycle' => $contractCycle
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

        $startDate = Carbon::parse( $validatedData['start_date']);
        $endDate = Carbon::parse( $validatedData['end_date']);

        $contractCycle->update([
            'value' => $validatedData['value'],
            'premium' => $validatedData['premium'],
            'notes' => $validatedData['notes'],
            'vendor' => $validatedData['vendor'],
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        if ($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file){
                $attachments = $file->store('attachments', 'public');
                $contractCycle->attachments()->create([
                    'attachments' => $attachments
                ]);
            }
        }


//        return redirect('contract/create')->with('success','asasas');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractCycle $contractCycle)
    {
        //
    }
}

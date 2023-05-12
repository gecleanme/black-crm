<?php

namespace App\Http\Controllers;

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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractCycle $contractCycle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractCycle $contractCycle)
    {
        //
    }
}

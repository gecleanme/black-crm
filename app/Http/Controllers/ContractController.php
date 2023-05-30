<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filters= $request->only(['title','value', 'cycle_value','premium','ends_within','type','is_active', 'client']);

        $contracts= Contract::with(['cycles','customer'])
            ->whereHas('cycles')->latest()->filter($filters)->paginate(10)->withQueryString();

        return Inertia::render('Contract/Index',[
            'contracts'=>$contracts,
            'filters' => $filters

        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {
        return Inertia::render('Contract/Create',[
            'makes' => Contract::MAKES,
            'models' => Contract::MODELS
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $contract = Contract::create($request->validate([
            'client_id' => 'required',
            'type' => 'required',
            'make' => 'required_if:type,"Auto"',
            'notes' => 'nullable|min:5|max:500',
            'prod_year' => 'required_if:type,"Auto"',
            'model' => 'required_if:type,"Auto"',
            'regno' => 'nullable',
            'pnum' => 'nullable',
            'lic_exp' => 'nullable',
            'title' => 'required|string|min:3|max:20'

        ]));


        return redirect('/cycle/create/'.$contract->id)->with('success', 'Success message');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        session()->put('contract_id', $contract->id);

        return Inertia::render('Contract/Edit',[
            'makes' => Contract::MAKES,
            'models' => Contract::MODELS,
            'contract' => $contract->load(['customer','cycles'])
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $contract->update($request->validate([
            'client_id' => 'required',
            'type' => 'required',
            'make' => 'required_if:type,"Auto"',
            'notes' => 'nullable|min:5|max:500',
            'prod_year' => 'required_if:type,"Auto"',
            'model' => 'required_if:type,"Auto"',
            'regno' => 'nullable',
            'pnum' => 'nullable',
            'lic_exp' => 'nullable',
            'title' => 'required|string|min:3|max:20'

        ]));


        return redirect('/contracts')->with('success', 'Success message');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function exporter(Request $request)
    {
        $filters= $request->only(['title','value', 'cycle_value','premium','ends_within','type','is_active', 'client']);

        $fileName = 'contracts_export'.today().'csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $columns = ['Title', 'Starts','Ends', 'Value','Premium','Customer','Type'];

        $callback = function () use ($filters, $request, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            $query = Contract::query();

            $query->filter($filters);



            $query->chunk(1000, function ($contracts) use ($file) {
                foreach ($contracts as $contract) {
                    $row = [
                        'title' => $contract->title,
                        'Starts' =>$contract->last_cycle->start_date,
                        'Ends' =>$contract->last_cycle->end_date,
                        'Value' =>$contract->last_cycle->value,
                        'Premium' =>$contract->last_cycle->premium,
                        'Customer' =>$contract->customer->name,
                        'Type' =>$contract->type
                    ];

                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}

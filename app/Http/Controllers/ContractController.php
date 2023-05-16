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
    public function index()
    {

        $contracts= Contract::with(['cycles','customer'])
            ->whereHas('cycles')
            ->paginate(10);

        return Inertia::render('Contract/Index',[
            'contracts'=>$contracts,

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
            'client' => 'required',
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

       session(['contract_id'=> $contract->id]);

        return redirect('/cycle/create');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }
}

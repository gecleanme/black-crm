<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query=Customer::query()
            ->when($request->query('search'),function ($q) use ($request) {
                $term=$request->query('search');
                $q->where('name','LIKE','%'.$term.'%');
            });

        if ($request->wantsJson()){
            return CustomerResource::collection($query->get());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        return Inertia::render('Customer/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Customer::create($request->validate([
           'name' => 'required|string|min:2|max:20',
           'cell'  => 'nullable|string|min:10|max:14',
            'sex' => 'required|string',
            'vip' => 'required|boolean',
            'type' => 'required|string',
            'risk_level' => 'nullable|integer',
            'ref' => 'string|nullable',
            'dob' => 'date|nullable',
            'secondary_phone' => 'nullable|string|min:10|max:14',

        ]));


    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}

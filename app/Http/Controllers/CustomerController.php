<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Vtiful\Kernel\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd(Customer::query()->latest()->first());

        $filters= $request->only(['name','type', 'risk_level']);
        $types = ['Business', 'Individual'];

         $query=Customer::query()
            ->when($request->query('search'),function ($q) use ($request) {
                $term=$request->query('search');
                $q->where('name','LIKE','%'.$term.'%');
            });

        if ($request->wantsJson()){
            return CustomerResource::collection($query->get());
        }

        return Inertia::render('Customer/Index',[
            'customers' => Customer::query()->latest()->filter($filters)->paginate(10)->withQueryString(),
            'filters' => $filters,
            'types' => $types
        ]);
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
        $customer= Customer::create($request->validate([
           'name' => 'required|string|min:2|max:20',
           'cell'  => 'required|string|min:10|max:14',
            'sex' => 'required|string',
            'vip' => 'required|boolean',
            'type' => 'required|string',
            'risk_level' => 'nullable|integer',
            'ref' => 'string|nullable',
            'dob' => 'date|nullable',
            'secondary_phone' => 'nullable|string|min:10|max:14',
            'notes' => 'string|nullable|min:3|max:400'

        ]));

        if ($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file){
                $attachments = $file->store('attachments', 'public');
                $customer->attachments()->create([
                    'attachments' => $attachments
                ]);
            }
        }

        return redirect('/customers')->with('success', 'Success message');



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
        $customer->load('attachments');
        return Inertia::render('Customer/Edit',[
            'customer' => $customer,
            'url' => env('APP_URL')
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->validate([
            'name' => 'required|string|min:2|max:20',
            'cell'  => 'required|string|min:10|max:14',
            'sex' => 'required|string',
            'vip' => 'required|boolean',
            'type' => 'required|string',
            'risk_level' => 'nullable|integer',
            'ref' => 'string|nullable',
            'dob' => 'date|nullable',
            'secondary_phone' => 'nullable|string|min:10|max:14',
            'notes' => 'string|nullable|min:3|max:400'

        ]));

        if ($request->hasFile('attachments')){
            foreach ($request->file('attachments') as $file){
                $attachments = $file->store('attachments', 'public');
                $customer->attachments()->create([
                    'attachments' => $attachments
                ]);
            }
        }


        return redirect('/customers')->with('success', 'Success message');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function exporter()
    {
        $fileName = 'customers_export'.today().'csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $columns = ['Name', 'Primary_Phone','Notes', 'VIP'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Customer::chunk(1000, function ($customers) use ($file) {
                foreach ($customers as $customer) {
                    $row = [
                        'name' => $customer->name,
                        'cell' => $customer->cell,
                        'Notes' => $customer->notes,
                        'VIP?' => $customer->vip ? 'Yes': 'No'
                    ];

                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


}

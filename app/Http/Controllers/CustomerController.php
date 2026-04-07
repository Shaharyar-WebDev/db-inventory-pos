<?php

namespace App\Http\Controllers;

use App\Models\Master\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::select('id', 'name', 'contact', 'customer_type')
            ->orderBy('name')
            ->get();

        return response()->json($customers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255|unique:customers,name',
            'contact' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::create([
            'name'          => $validated['name'],
            'contact'       => $validated['contact'] ?? null,
            'address'       => $validated['address'] ?? null,
            'customer_type' => 'registered',
        ]);

        return response()->json([
            'id'            => $customer->id,
            'name'          => $customer->name,
            'contact'       => $customer->contact,
            'customer_type' => $customer->customer_type,
        ], 201);
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

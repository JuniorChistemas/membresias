<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listCustomers()
    {
        $customers = Customer::orderBy('id', 'asc')->paginate(10);
        return response()->json([
            'success' => true,
            'customers' => CustomerResource::collection($customers),
            'pagination' => [
                'total' => $customers->total(),
                'current_page' => $customers->currentPage(),
                'per_page' => $customers->perPage(),
                'last_page' => $customers->lastPage(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem()
            ],
        ]);
    }


    public function index()
    {
        return Inertia::render('Panel/Customers/indexCustomer');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'customer' => new CustomerResource($customer),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'customer' => new CustomerResource($customer),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'customer' => new CustomerResource($customer),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
        ]);
    }
}

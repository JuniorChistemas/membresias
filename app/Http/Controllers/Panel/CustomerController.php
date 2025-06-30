<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;
use App\Imports\CustomerImport;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listCustomers(Request $request)
    {
        $search = $request->input('search', '');
        $customers = Customer::when($search, function ($query) use ($search) {
            $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orwhere('code', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);

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
        return Inertia::render('Panel/Customers/indexCustomers');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente',
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
            'message' => 'Cliente actualizado exitosamente',
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
            'message' => 'Cliente eliminado exitosamente',
        ]);
    }
    
    //EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new CustomersExport, 'Registro de clientes.xlsx');
    }

    //IMPORT EXCEL
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new CustomerImport, $request->file('archivo'));

        return response()->json([
            'message' => 'Importacion de clientes realizado correctamente.',
        ]);
    }

    public function getAll()
    {
        $customers = Customer::orderBy('id')->get();

        return response()->json([
            'success' => true,
            'customers' => CustomerResource::collection($customers),
        ]);
    }


}

<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\PaymentMethodResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentMethodsExport;
use App\Imports\PaymentMethodsImport;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listPaymentMethods(Request $request)
    {
        $search = $request->input('search', '');
        $paymentMethods = PaymentMethod::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);

        return response()->json([
            'success' => true,
            'paymentMethods' => PaymentMethodResource::collection($paymentMethods),
            'pagination' => [
                'total' => $paymentMethods->total(),
                'current_page' => $paymentMethods->currentPage(),
                'per_page' => $paymentMethods->perPage(),
                'last_page' => $paymentMethods->lastPage(),
                'from' => $paymentMethods->firstItem(),
                'to' => $paymentMethods->lastItem()
            ],
        ]);
    }
    public function index()
    {
        return Inertia::render('Panel/PaymentMethods/indexPaymentMethods');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Método de pago creado exitosamente',
            'paymentMethod' => new PaymentMethodResource($paymentMethod),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return response()->json([
            'success' => true,
            'paymentMethod' => new PaymentMethodResource($paymentMethod),
        ]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Método de pago actualizado exitosamente',
            'paymentMethod' => new PaymentMethodResource($paymentMethod),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return response()->json([
            'success' => true,
            'message' => 'Método de pago eliminado exitosamente',
        ]);
    }

    //EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new PaymentMethodsExport, 'payment_methods.xlsx');
    }

    //IMPORT EXCEL
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        Excel::import(new PaymentMethodsImport, $request->file('archivo'));     
        return response()->json([
            'success' => true,
            'message' => 'Métodos de pago importados exitosamente',
        ]);
    }
}

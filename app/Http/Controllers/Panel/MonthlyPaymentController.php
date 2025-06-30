<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MonthlyPayment;
use App\Http\Requests\StoreMonthlyPaymentRequest;
use App\Http\Requests\UpdateMonthlyPaymentRequest;
use App\Http\Resources\MonthlyPaymentResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonthlyPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Panel/MonthlyPayments/index');
    }

    public function listMonthlyPayments(Request $request)
    {
        $search = $request->input('search', '');

        $payments = MonthlyPayment::with(['customer', 'typeMembership', 'paymentMethod', 'coach'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'monthly_payments' => MonthlyPaymentResource::collection($payments),
            'pagination' => [
                'total' => $payments->total(),
                'current_page' => $payments->currentPage(),
                'per_page' => $payments->perPage(),
                'last_page' => $payments->lastPage(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem()
            ],
        ]);
    }

    public function store(StoreMonthlyPaymentRequest $request)
    {
        $payment = MonthlyPayment::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado exitosamente.',
            'monthly_payment' => new MonthlyPaymentResource($payment),
        ]);
    }

    public function show(MonthlyPayment $monthlyPayment)
    {
        return response()->json([
            'success' => true,
            'monthly_payment' => new MonthlyPaymentResource($monthlyPayment->load(['customer', 'typeMembership', 'paymentMethod', 'coach'])),
        ]);
    }

    public function update(UpdateMonthlyPaymentRequest $request, MonthlyPayment $monthlyPayment)
    {
        $monthlyPayment->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pago actualizado exitosamente.',
            'monthly_payment' => new MonthlyPaymentResource($monthlyPayment),
        ]);
    }

    public function destroy(MonthlyPayment $monthlyPayment)
    {
        $monthlyPayment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pago eliminado exitosamente.',
        ]);
    }
}

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
        return Inertia::render('Panel/MonthlyPayments/indexMonthlyPayments');
    }

    public function listMonthlyPayments(Request $request)
    {
        $search = $request->input('search', '');

        // Si search es un JSON vacío, ignóralo
        if ($search === '{}' || $search === 'null' || $search === null) {
            $search = '';
        }
        // O para soporte futuro:
        if (is_string($search) && str_starts_with($search, '{')) {
            $decoded = json_decode($search, true);
            if (empty($decoded)) {
                $search = '';
            }
        }

        // ...el resto igual
        $typeMembershipId = $request->input('typeMembershipId');
        $paymentMethodId = $request->input('paymentMethodId');
        $coachId = $request->input('coachId');
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');

        $payments = MonthlyPayment::with(['customer', 'typeMembership', 'paymentMethod', 'coach'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            // ...los demás filtros
            ->orderBy('id', 'asc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'monthlyPayments' => MonthlyPaymentResource::collection($payments),
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
            'monthly_payment' => new MonthlyPaymentResource($payment->load(['customer', 'typeMembership', 'paymentMethod', 'coach'])),
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

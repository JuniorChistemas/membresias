<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\DailySessionLog;
use App\Http\Requests\StoreDailySessionLogRequest;
use App\Http\Requests\UpdateDailySessionLogRequest;
use Illuminate\Http\Request;
use App\Http\Resources\DailySessionLogResource;
use Inertia\Inertia;

class DailySessionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listDailySessionLogs(Request $request)
    {
        $paymentMethodId = $request->input('payment_method_id');
        $startDate = $request->input('start_date'); // formato esperado: YYYY-MM-DD
        $endDate = $request->input('end_date');     // formato esperado: YYYY-MM-DD

        $dailySessionLogs = DailySessionLog::with('paymentMethod')
            // Filtrar por método de pago si se envía
            ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                $query->where('payment_method_id', $paymentMethodId);
            })
            // Filtrar por rango de fechas si ambos están presentes
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('registered_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            // Ordenar por fecha descendente (más recientes primero)
            ->orderBy('registered_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'dailySessionLogs' => DailySessionLogResource::collection($dailySessionLogs),
            'pagination' => [
                'total' => $dailySessionLogs->total(),
                'current_page' => $dailySessionLogs->currentPage(),
                'per_page' => $dailySessionLogs->perPage(),
                'last_page' => $dailySessionLogs->lastPage(),
                'from' => $dailySessionLogs->firstItem(),
                'to' => $dailySessionLogs->lastItem()
            ],
        ]);
    }
    
    public function index()
    {
        return Inertia::render('Panel/DailySessionLogs/indexDailySessionLogs');
    }

    public function store(StoreDailySessionLogRequest $request)
    {
        $data = $request->validated();
        $data['registered_at'] = now(); // <-- asigna la fecha con timezone correcto

        $dailySessionLog = DailySessionLog::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Registro de sesión creado exitosamente',
            'dailySessionLog' => new DailySessionLogResource($dailySessionLog),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DailySessionLog $dailySessionLog)
    {
        return response()->json([
            'success' => true,
            'dailySessionLog' => new DailySessionLogResource($dailySessionLog),
        ]);
    }

    public function update(UpdateDailySessionLogRequest $request, DailySessionLog $dailySessionLog)
    {
        $dailySessionLog->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Registro de sesión actualizado exitosamente',
            'dailySessionLog' => new DailySessionLogResource($dailySessionLog),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailySessionLog $dailySessionLog)
    {
        $dailySessionLog->delete();
        return response()->json([
            'success' => true,
            'message' => 'Registro de sesión eliminado exitosamente',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\DailySessionLog;
use App\Http\Requests\StoreDailySessionLogRequest;
use App\Http\Requests\UpdateDailySessionLogRequest;
use Illuminate\Http\Request;
use App\Http\Resources\DailySessionLogResource;

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
            ->when($paymentMethodId, function ($query) use ($paymentMethodId) {
                $query->where('payment_method_id', $paymentMethodId);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('registered_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
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
        return Intertia::render('Panel/DailySessionLogs/indexDailySessionLogs');
    }

    public function store(StoreDailySessionLogRequest $request)
    {
        $dailySessionLog = DailySessionLog::create($request->validated());
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

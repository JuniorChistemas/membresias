<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use App\Http\Resources\CoachResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoachesExport;
use App\Imports\CoachesImport;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listCoaches(Request $request)
    {
        $serach = $request->input('search', '');
        $coaches = Coach::when($serach, function ($query) use ($serach) {
            $query->where('name', 'like', '%' . $serach . '%')
                ->orwhere('dni', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);

        return response()->json([
            'success' => true,
            'coaches' => CoachResource::collection($coaches),
            'pagination' => [
                'total' => $coaches->total(),
                'current_page' => $coaches->currentPage(),
                'per_page' => $coaches->perPage(),
                'last_page' => $coaches->lastPage(),
                'from' => $coaches->firstItem(),
                'to' => $coaches->lastItem()
            ],
        ]);
    }
    
    public function index()
    {
        return Inertia::render('Panel/Coaches/indexCoaches');
    }

    public function store(StoreCoachRequest $request)
    {
        $coach = Coach::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Entrenador creado exitosamente',
            'coach' => new CoachResource($coach),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach)
    {
        return response()->json([
            'success' => true,
            'coach' => new CoachResource($coach),
        ]);
    }

    public function update(UpdateCoachRequest $request, Coach $coach)
    {
        $coach->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Entrenador actualizado exitosamente',
            'coach' => new CoachResource($coach),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coach $coach)
    {
        $coach->delete();
        return response()->json([
            'success' => true,
            'message' => 'Entrenador eliminado exitosamente',
        ]);
    }

    //EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new CoachesExport, 'Registro de entrenadores.xlsx');
    }

    //IMPORT EXCEL
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new CoachesImport, $request->file('archivo'));

        return response()->json([
            'message' => 'Importación de entrenadores realizado correctamente.',
        ]);
    }

    public function getAll()
    {
        $coaches = Coach::orderBy('id')->get();

        return response()->json([
            'success' => true,
            'coaches' => CoachResource::collection($coaches),
        ]);
    }

}

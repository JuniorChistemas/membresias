<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeMembershipRequest;
use App\Http\Requests\UpdateTypeMembershipRequest;
use App\Http\Resources\TypeMembershipResource;
use App\Models\TypeMembership;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TypeMembershipsExport;
use App\Imports\TypeMembershipsImport;

class TypeMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listTypeMemberships(Request $request)
    {
        $search = $request->input('search', '');
        $typeMemberships = TypeMembership::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);
        return response()->json([
            'success' => true,
            'typeMemberships' => TypeMembershipResource::collection($typeMemberships),
            'pagination' => [
                'total' => $typeMemberships->total(),
                'current_page' => $typeMemberships->currentPage(),
                'per_page' => $typeMemberships->perPage(),
                'last_page' => $typeMemberships->lastPage(),
                'from' => $typeMemberships->firstItem(),
                'to' => $typeMemberships->lastItem()
            ],
        ]);
    }


    public function index()
    {
        return Inertia::render('Panel/TypeMemberships/indexTypeMemberships');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeMembershipRequest $request)
    {
        $typeMembership = TypeMembership::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Tipo de membresía creado exitosamente',
            'typeMembership' => new TypeMembershipResource($typeMembership),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeMembership $typeMembership)
    {
        return response()->json([
            'success' => true,
            'typeMembership' => new TypeMembershipResource($typeMembership),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeMembershipRequest $request, TypeMembership $typeMembership)
    {
        $typeMembership->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Tipo de membresía actualizado exitosamente',
            'typeMembership' => new TypeMembershipResource($typeMembership),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeMembership $typeMembership)
    {
        $typeMembership->delete();
        return response()->json([
            'success' => true,
            'message' => 'Tipo de membresía eliminado exitosamente',
        ]);
    }

    //EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new TypeMembershipsExport, 'Registro de tipo de membresias.xlsx');
    }

    //IMPORT EXCEL
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        Excel::import(new TypeMembershipsImport, $request->file('archivo'));
        return response()->json([
            'success' => true,
            'message' => 'Importación de tipo de membresias realizado correctamente.',
        ]);
    }
}

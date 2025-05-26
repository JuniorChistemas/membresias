<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocalRequest;
use App\Http\Requests\UpdateLocalRequest;
use App\Http\Resources\LocalResource;
use App\Models\Local;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listLocals(Request $request)
    {
        $search = $request->input('search', '');
        $locals = Local::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);
        return response()->json([
            'success' => true,
            'locals' => LocalResource::collection($locals),
            'pagination' => [
                'total' => $locals->total(),
                'current_page' => $locals->currentPage(),
                'per_page' => $locals->perPage(),
                'last_page' => $locals->lastPage(),
                'from' => $locals->firstItem(),
                'to' => $locals->lastItem()
            ],
        ]);
    }

    public function index()
    {
        return Inertia::render('Panel/Locals/indexLocals');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocalRequest $request)
    {
        $local = Local::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Local created successfully',
            'local' => new LocalResource($local),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Local $local)
    {
        return response()->json([
            'success' => true,
            'local' => new LocalResource($local),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocalRequest $request, Local $local)
    {
        $local->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Local updated successfully',
            'local' => new LocalResource($local),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Local $local)
    {
        $local->delete();
        return response()->json([
            'success' => true,
            'message' => 'Local deleted successfully',
        ]);
    }
}

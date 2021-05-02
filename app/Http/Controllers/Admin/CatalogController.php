<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $catalogs = Catalog::paginate(20);

        return Inertia::render('Admin/Catalogs/Index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Catalogs/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'sometimes|required|boolean',
            'name'      => 'nullable',
            'semester'  => 'required|in:spring,summer,fall,winter',
            'year'      => 'required|digits:4|integer|min:2000|max:'.(date('Y') + 1),
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $catalog = Catalog::create($validated);

        return redirect()->route('admin.catalogs.show', $catalog);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Catalog $catalog
     *
     * @return \Inertia\Response
     */
    public function show(Catalog $catalog)
    {
        return Inertia::render('Admin/Catalogs/Show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Catalog $catalog
     *
     * @return \Inertia\Response
     */
    public function edit(Catalog $catalog)
    {
        return Inertia::render('Admin/Catalogs/Edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Catalog      $catalog
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Catalog $catalog)
    {
        $validated = $request->validate([
            'is_active' => 'sometimes|required|boolean',
            'name'      => 'nullable',
            'semester'  => 'required|in:spring,summer,fall,winter',
            'year'      => 'required|digits:4|integer|min:2000|max:'.(date('Y') + 1),
        ]);

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = false;
        }

        $catalog->update($validated);

        return redirect()->route('admin.catalogs.show', $catalog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Catalog $catalog
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Catalog $catalog)
    {
        $catalog->delete();

        return redirect()->action([static::class, 'index']);
    }
}

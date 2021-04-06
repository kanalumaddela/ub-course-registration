<?php

namespace App\Http\Controllers;


use App\Models\Catalog;
use Inertia\Inertia;

class CatalogController
{
    public function index()
    {
        /**
         * @var \Illuminate\Pagination\Paginator $catalogs
         */
        $catalogs = Catalog::where('is_active', 1)->orderBy('semester')->paginate(15);

//        dd(compact('catalogs'));

        return Inertia::render('Catalogs', compact('catalogs'));
    }

    public function view(Catalog $catalog)
    {
        return $catalog;
    }

    public function search()
    {

    }
}

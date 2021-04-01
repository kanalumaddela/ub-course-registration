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
        $catalogs = Catalog::latest('start')->paginate(15);

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

<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\catalogue;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorecatalogueRequest;
use App\Http\Requests\UpdatecatalogueRequest;

class CatalogueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = catalogue::all();

        return Inertia::render('Catalogue/Index', [
            // 'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecatalogueRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(catalogue $catalogue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(catalogue $catalogue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecatalogueRequest $request, catalogue $catalogue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(catalogue $catalogue)
    {
        //
    }
}

<?php

namespace EID\Http\Controllers;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\commodity_categories;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class commodity_categoriesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $commodity_categories = commodity_categories::paginate(15);

        return view('commodity_categories.index', compact('commodity_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('commodity_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        commodity_categories::create($request->all());

        Session::flash('flash_message', 'commodity_categories successfully added!');

        return redirect('commodity_categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $commodity_category = commodity_categories::findOrFail($id);

        return view('commodity_categories.show', compact('commodity_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $commodity_category = commodity_categories::findOrFail($id);

        return view('commodity_categories.edit', compact('commodity_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $commodity_category = commodity_categories::findOrFail($id);
        $commodity_category->update($request->all());

        Session::flash('flash_message', 'commodity_categories successfully updated!');

        return redirect('commodity_categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        commodity_categories::destroy($id);

        Session::flash('flash_message', 'commodity_categories successfully deleted!');

        return redirect('commodity_categories');
    }

}

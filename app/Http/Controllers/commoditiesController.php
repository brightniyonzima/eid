<?php

namespace EID\Http\Controllers;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\commodities;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class commoditiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $commodities = commodities::paginate(15);

        return view('commodities.index', compact('commodities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('commodities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        commodities::create($request->all());

        Session::flash('flash_message', 'commodities successfully added!');

        return redirect('commodities');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $commodity = commodities::findOrFail($id);

        return view('commodities.show', compact('commodity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $commodity = commodities::findOrFail($id);

        return view('commodities.edit', compact('commodity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $commodity = commodities::findOrFail($id);
        $commodity->update($request->all());

        Session::flash('flash_message', 'commodities successfully updated!');

        return redirect('commodities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        commodities::destroy($id);

        Session::flash('flash_message', 'commodities successfully deleted!');

        return redirect('commodities');
    }

}

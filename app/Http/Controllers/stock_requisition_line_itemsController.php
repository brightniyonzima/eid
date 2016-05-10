<?php

namespace EID\Http\Controllers;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\stock_requisition_line_items;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class stock_requisition_line_itemsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $stock_requisition_line_items = stock_requisition_line_items::paginate(15);

        return view('stock_requisition_line_items.index', compact('stock_requisition_line_items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('stock_requisition_line_items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        stock_requisition_line_items::create($request->all());

        Session::flash('flash_message', 'stock_requisition_line_items successfully added!');

        return redirect('stock_requisition_line_items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $stock_requisition_line_item = stock_requisition_line_items::findOrFail($id);

        return view('stock_requisition_line_items.show', compact('stock_requisition_line_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $stock_requisition_line_item = stock_requisition_line_items::findOrFail($id);

        return view('stock_requisition_line_items.edit', compact('stock_requisition_line_item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $stock_requisition_line_item = stock_requisition_line_items::findOrFail($id);
        $stock_requisition_line_item->update($request->all());

        Session::flash('flash_message', 'stock_requisition_line_items successfully updated!');

        return redirect('stock_requisition_line_items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        stock_requisition_line_items::destroy($id);

        Session::flash('flash_message', 'stock_requisition_line_items successfully deleted!');

        return redirect('stock_requisition_line_items');
    }

}

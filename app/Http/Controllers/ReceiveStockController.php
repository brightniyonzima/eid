<?php

namespace EID\Http\Controllers;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\ReceiveStock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class ReceiveStockController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $receivestocks = ReceiveStock::paginate(15);

        return view('receivestock.index', compact('receivestocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('receivestock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['commodity_id' => 'required', 'qty_rcvd' => 'required', ]);

        ReceiveStock::create($request->all());

        Session::flash('flash_message', 'ReceiveStock successfully added!');

        return redirect('receivestock');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $receivestock = ReceiveStock::findOrFail($id);

        return view('receivestock.show', compact('receivestock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $receivestock = ReceiveStock::findOrFail($id);

        return view('receivestock.edit', compact('receivestock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['commodity_id' => 'required', 'qty_rcvd' => 'required', ]);

        $receivestock = ReceiveStock::findOrFail($id);
        $receivestock->update($request->all());

        Session::flash('flash_message', 'ReceiveStock successfully updated!');

        return redirect('receivestock');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        ReceiveStock::destroy($id);

        Session::flash('flash_message', 'ReceiveStock successfully deleted!');

        return redirect('receivestock');
    }

}

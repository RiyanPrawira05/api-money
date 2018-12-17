<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $money = Money::all();
        return $money;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $detail = $request->operator == 'pemasukkan' ? '+' : '-';
        $time = $request->waktu ? Carbon::parse($request->waktu)->format('y-m-d h:i:s') : '';
        $jumlah = filter_var($request->jumlah, FILTER_SANITIZE_NUMBER_FLOAT); 
        $money = Money::create([
            'jumlah' => $jumlah,
            'keterangan' => $request->keterangan, 
            'operator' => $detail,
            'waktu' => $time,
        ]);
        return $money;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $money = Money::find($id);
        return $money;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $detail = $request->operator == 'pemasukkan' ? '+' : '-';
        $time = $request->waktu ? Carbon::parse($request->waktu)->format('y-m-d h:i:s') : '';
        $jumlah = filter_var($request->jumlah, FILTER_SANITIZE_NUMBER_FLOAT); 
        $money = Money::find($id)->update([
            'jumlah' => $jumlah,
            'keterangan' => $request->keterangan, 
            'operator' => $detail,
            'waktu' => $time,
        ]);
        return $money;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $money = Money::find($id)->delete();
        return $money;
    }

    public function delete(Request $request)
    {
        //
    }
}

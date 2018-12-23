<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Money;
use Carbon\Carbon;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Money::query();
        if ($request->filled('search')) {
            $search = Carbon::parse($request->search)->format('y-m-d');
            $money = $result->whereDate('waktu', $search);
        }

        // } else {
        //     return 'Data empty!';
        // }

        $money = Money::orderBy('waktu', 'DESC')->paginate(5);
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
        $this->validate($request, [
            'jumlah' => 'required|numeric',
            'operator' => 'required|string',
            'keterangan' => 'min:3|max:150|nullable',
            'waktu' => 'required',
        ]);

        $detail = $request->operator == 'pemasukkan' ? '+' : '-';
        $time = $request->waktu ? Carbon::parse($request->waktu)->format('y-m-d h:i:s') : '';
        $jumlah = filter_var($request->jumlah, FILTER_SANITIZE_NUMBER_FLOAT); 
        $money = Money::create([
            'jumlah' => $jumlah,
            'keterangan' => $request->keterangan, 
            'operator' => $detail,
            'waktu' => $time,
        ]);
        return 'Data successfully added : '.$money;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $money = Money::find($id);

        // $money->request->operator = $request->operator == '+' ? 'pemasukkan' : 'pengeluaran'; (cara manipulasi agar data di postman operator nya tidak '+' bagaimana yah? wkoko khususnya di controller ini karena biasa manupulasi ngelogic di blade nya saya..)

        if (filled($money)){  
            return $money;
        } else {
            return 'Data id empty!';
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $money = Money::find($id);
    //     return $money;
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'jumlah' => 'numeric',
            'operator' => 'string',
            'keterangan' => 'min:3|max:150|nullable',
            'waktu' => 'required',
        ]);

        $detail = $request->operator == 'pemasukkan' ? '+' : '-';
        $time = $request->waktu ? Carbon::parse($request->waktu)->format('y-m-d h:i:s') : '';
        $jumlah = filter_var($request->jumlah, FILTER_SANITIZE_NUMBER_FLOAT); 
        $data = Money::find($id);
        $data->jumlah = $jumlah;
        $data->keterangan = $request->keterangan;
        $data->operator = $detail;
        $data->waktu = $time;
        $data->save();
        return 'Data successfully updated : '.$data;
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
        return 'Data successfully deleted';
    }

    public function delete(Request $request)
    {
        if (filled($request->ceklis))
        {
            $ceklis = $request->ceklis;
            $money = Money::whereIn('id', $ceklis)->delete();
            return 'Data catatan finance sudah dihapus';

        } else {
            return 'Tidak ada data yang ingin dihapus, silahkan cek kembali';
        }
    }

    public function laporan()
    {
        $money = Money::whereMonth('created_at', '=', '12')->get();
        $total = 0;
        foreach ($money as $key => $value) {
            if ($value->operator == '+') {
                $total += $value->jumlah;
            } else {
                $total -= $value->jumlah;
            }
        }
        return 'Total jumlah pengeluaran dan pemasukkan bulan ini : '.$total;
    }
}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Transaksi;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
  public function __construct()
  {
    if(Auth::user()){
        if(Auth::user()->role == "user"){
          return redirect()->back();
        }
      }
    else{
      return view('auth.login');
    }
  }

  public function index(){
    $counter = 1;
    $transaksi = Transaksi::query()->where('status', 'Menunggu Dikonfirmasi')->latest();
    if (request()->has("search") && strlen(request()->query("search")) >= 1) {
      $transaksi->where(
        "transaksis.nama", "like", "%" . request()->query("search") . "%"
      );
    }

    $pagination = 4;
    $transaksi = $transaksi->paginate($pagination);
    if( request()->has('page') && request()->get('page') > 1){
      $counter += (request()->get('page')- 1) * $pagination;
    }
    return view('admin.transaksi', compact('transaksi'));
  }

  public function terima(Request $request, $id){
    $data = Transaksi::find($id);
    $data->status = "Jadwal Wisata Diterima";
    $data->save();
    $request->session()->flash('message','Berhasil Menerima Jadwal Wisata');
    return redirect()->back();
  }

  public function tolak(Request $request, $id){
    $data = Transaksi::find($id);
    $data->status = "Jadwal Wisata Ditolak";
    $data->save();
    $request->session()->flash('message','Berhasil Menolak Data');
    return redirect()->back();
  }
}

<?php

namespace App\Http\Controllers;

use App\Models\foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foto = foto::latest()->paginate(2);
        return view('foto.index', compact ('foto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('foto.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
            'caption' =>['nullable', 'string', 'max:100']    
        ]);
    $user = auth()->user();
    $foto = new foto;
    $foto->created_by = $user->id;
    $foto->foto =$request->file('foto')->store('foto');
    $foto->caption = $request->caption;
    $foto->save();

    //redirect ke halaman index dan tampilkan alert
    return redirect(route('foto.index'))->with('success','foto berhasil di tampilkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(foto $foto)
    {
        if($foto->foto){
            Storage::delete($foto->foto);
        }
        if($foto->delete()) {
            return redirect(route('foto.index'))->with('success','foto berhasil dihapus');
        }
        return redirect(route('foto.index'))->with('error', 'foto gagal dihapus');
    }
}
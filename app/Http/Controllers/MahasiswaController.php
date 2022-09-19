<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('mahasiswa.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mahasiswa.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|alpha_dash|min:4|max:20|unique:App\Models\Mahasiswa',
            'nama' => 'required|string|max:50',
            'berkas' => 'required|mimes:jpg,png|max:100'
        ];

        $validator =  Validator::make($request->all(), $rules);

        if($validator->fails()){
            return view('mahasiswa.index')->with('error', $validator->errors());
        }

        $file = $request->file('berkas');
        $image_name = '';
        if($file){
            $image_name = $file->store('images', 'public');
        }

        Mahasiswa::create([
            'username' => $request->input('username'),
            'nama' => $request->input('nama'),
            'avatar' => $image_name
        ]);

        return view('mahasiswa.index')->with('success', 'Artikel berhasil disimpan');
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
        $data =  Mahasiswa::find($id);

        return (!$data)? view('no_data') :
                    view('mahasiswa.edit')
                    ->with('id', $id)
                    ->with('data', $data);
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
        $rules = [
            'username' => 'required|alpha_dash|min:4|max:20|unique:App\Models\Mahasiswa,id,'.$id,
            'nama' => 'required|string|max:50',
            'berkas' => 'required|mimes:jpg,png|max:100'
        ];

        $validator =  Validator::make($request->all(), $rules);

        $data =  Mahasiswa::find($id);

        if($validator->fails()){
            return view('mahasiswa.edit')
                    ->with('error', $validator->errors())
                    ->with('id', $id)
                    ->with('data', $data);
        }

        $file = $request->file('berkas');
        $image_name = '';
        if($file){
            $image_name = $file->store('images', 'public');

            // if(Storage::exists('public/' . $data->avatar)){
            //     Storage::delete('public/' . $data->avatar);
            // }
        }

        Mahasiswa::where('id', $id)
                ->update([
                    'username' => $request->input('username'),
                    'nama' => $request->input('nama'),
                    'avatar' => $image_name
                ]);


        return  view('mahasiswa.edit')
            ->with('id', $id)
            ->with('data', $data)
            ->with('success','data berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cetak_pdf()
    {
        $data = Mahasiswa::all();
        
        //return view('cetak_pdf', ['data' => $data]);

        $pdf = Pdf::loadView('cetak_pdf', ['data' => $data]);
        return $pdf->stream();
    }
}

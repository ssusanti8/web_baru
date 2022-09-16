<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('product.index', compact('products'), [
            'title' => 'Product'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create', [
            'title' => 'Tambah Product'
        ]);
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
            'namaproduk'     => 'required',
            'gambar'       => 'required|image|mimes:png,jpg,jpeg',
            'deskripsi'         => 'required'
        ]);
    
        //upload gambar
        $gambar = $request->file('gambar');
        $gambar->storeAs('public/products', $gambar->hashName());
    
        $product = Product::create([
            'namaproduk' => $request->namaproduk,
            'gambar'   => $gambar->hashName(),
            'deskripsi'     => $request->deskripsi
        ]);
    
        if($product){
            //redirect dengan pesan sukses
            return redirect()->route('product.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('product.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
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
    public function edit(Product $product)
    {
        return view('product.edit', compact('product'), [
            'title' => 'Edit Product'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'namaproduk'     => 'required',
            'gambar'       => 'required|image|mimes:png,jpg,jpeg',
            'deskripsi'         => 'required'
        ]);
    
        //get data Product by ID
        $product = Product::findOrFail($product->id);
    
        if($request->file('gambar') == "") {
    
            $product->update([
                'namaproduk' => $request->namaproduk,
                'deskripsi'     => $request->deskripsi
            ]);
    
        } else {
    
            //hapus old image
            Storage::disk('local')->delete('public/products/'.$product->gambar);
    
            //upload new image
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/products', $gambar->hashName());
    
            $product->update([
                'namaproduk' => $request->namaproduk,
                'gambar'   => $gambar->hashName(),
                'deskripsi'     => $request->deskripsi
            ]);
    
        }
    
        if($product){
            //redirect dengan pesan sukses
            return redirect()->route('product.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('product.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::disk('local')->delete('public/products/'.$product->gambar);
        $product->delete();

        if($product){
            //redirect dengan pesan sukses
            return redirect()->route('product.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('product.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}

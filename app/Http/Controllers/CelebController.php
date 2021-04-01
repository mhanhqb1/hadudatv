<?php

namespace App\Http\Controllers;

use App\Models\Celeb;
use Illuminate\Http\Request;

class CelebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $celebs = Celeb::orderBy('name')
                         ->paginate(20);
        return view('celebs')->with('celebs', $celebs);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Celeb $id
     * @return \Illuminate\Http\Response
     */
    public function show(Celeb $id)
    {
        $celeb = Celeb::find($id);
        return view('single_celeb')->with('celeb', $celeb);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Celeb  $celeb
     * @return \Illuminate\Http\Response
     */
    public function edit(Celeb $celeb)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Celeb  $celeb
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Celeb $celeb)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Celeb  $celeb
     * @return \Illuminate\Http\Response
     */
    public function destroy(Celeb $celeb)
    {
        //
    }
}

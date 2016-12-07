<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \DB::table('items')->insert(
            [
                'name' => 'samsung edge 7',
                'receiver_name' => 'saiful',
                'receiver_phone_number' => '+625254318976',
                'pickup_address' => 'gajahmada plaza, sawah besar, jakarta pusat',
                'destination_address' => 'pasaraya blok m, jakarta selatan',
                'status' => 'new',
            ],
            [
                'name' => 'macbook pro Retina 15 inch',
                'receiver_name' => 'sulaiman',
                'receiver_phone_number' => '+621309876549',
                'pickup_address' => 'Mangga 2 square, Jakarta pusat',
                'destination_address' => 'cinere mall, depok jawa barat',
                'status' => 'new',
            ],
            [
                'name' => 'nasi goreng bakmi gm',
                'receiver_name' => 'kartono',
                'receiver_phone_number' => '+621344553322',
                'pickup_address' => 'Bakmi GM blok m, seberang menara sentraya',
                'destination_address' => 'pom bensin radio dalam',
                'status' => 'new',
            ]
        );

        return ['a' => 'b'];
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
        return ['c' => 'd'];
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
        //
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
        //
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
}

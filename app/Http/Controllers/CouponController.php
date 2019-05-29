<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Coupon;
use App\Group;
use ghun2\CouponCodeGenerator\Facades\CouponCodeGenerator;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $coupons = Coupon::paginate(100);
        $users = DB::table('users')->get();

        return view('coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('coupons.create');
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
        $validatedData = $request->validate([
            'coupon_code_prefix' => 'required|max:3',
        ]);
        $prefix = $request->input('coupon_code_prefix');

        $users = DB::table('users')->pluck('id');
        $groups = DB::table('groups')->max('group');
        if (!$groups){
            $groups = "A";
        }
        else{
            ++$groups;
        }

//        echo $users;
//        echo $groups;

//        $coupon_gen = CouponCodeGenerator::generator($prefix,"S",$users);
        for($i=0;$i<10;$i++){
            $data = CouponCodeGenerator::generate_coupons(10000,$prefix,$groups,$users);
            Coupon::insert($data);
        }
        $in_group = array('prefix' => $prefix, 'group' => $groups, 'coupon_num' => 100000);
        Group::create($in_group);

//        $coupon = Coupon::create($coupon_gen);

        return redirect('/coupons')->with('success', 'Book is successfully saved');
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

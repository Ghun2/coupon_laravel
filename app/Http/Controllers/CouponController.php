<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $users = DB::table('users')->pluck('name','id')->all();

        return view('coupons.index', compact(['coupons','users']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $role = Auth::user()->name;

        // Check user role
        switch ($role) {
            case 'admin':
                return view('coupons.create');
                break;
            default:
                return redirect('/coupons')->with('success', 'You are not admin user');
                break;
        }
        ;
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
        $prefix = strtoupper($request->input('coupon_code_prefix'));

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

        return redirect('/coupons')->with('success', 'Coupon is successfully saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $coupons = Coupon::where('code','0')->first();
        $users = DB::table('users')->pluck('name','id')->all();

//        var_dump($coupons);

        return view('coupons.show', compact(['coupons','users']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
        //

        $group = strtoupper($request->input('group'));
//        var_dump($group);
//
        $coupons = Coupon::where('group',$group)->paginate(100);
        $users = DB::table('users')->pluck('name','id')->all();

        return view('coupons.index', compact(['coupons','users']));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
//        //
        $validatedData = $request->validate([
            'coupon_code' => 'required|alpha_num|min:16|max:16',
        ]);
        $code = strtoupper($request->input('coupon_code'));

        $coupons = Coupon::where('code',$code)->first();
        $users = DB::table('users')->pluck('name','id')->all();

        return view('coupons.show', compact(['coupons','users']));
    }
}

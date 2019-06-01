<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Coupon;
use App\Group;
use ghun2\CouponCodeGenerator\Facades\CouponCodeGenerator;

    // 핵심 컨트롤러 쿠폰 생성,조회,탐색 등의 프로세싱
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        // 쿠폰 코드 리스트 페이지 admin계정만 접근 가능
    public function index()
    {
        //
        // 현재 로그인인 된 계정 이름
        $role = Auth::user()->name;

        // Check user role
        switch ($role) {
            case 'admin':   //현재 계정이 admin 이라면
                $coupons = Coupon::paginate(100);
                $users = DB::table('users')->pluck('name','id')->all();

                return view('coupons.index', compact(['coupons','users']));

                break;
            default:        //admin을 제외한 나머지 유저들
                return redirect('/coupons/show')->with('success', 'You are not admin user');
                break;
        }
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        // 쿠폰 코드 생성 페이지 admin계정만 접근 가능
    public function create()
    {
        //
        // 현재 로그인인 된 계정 이름
        $role = Auth::user()->name;

        // Check user role
        switch ($role) {
            case 'admin':   //현재 계정이 admin 이라면 create 페이지
                return view('coupons.create');
                break;
            default:        //admin을 제외한 나머지 유저들은 홈으로 보내고 admin이 아니라는 메시지 보여주기
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
        // 쿠폰 코드 생성 버튼 클릭
    public function store(Request $request)
    {
        // 입력칸이 채워져있는지,문자열 3자리 인지 판별
        $validatedData = $request->validate([
            'coupon_code_prefix' => 'required|max:3|min:3',
        ]);
        // 대문자로 바꿔주고
        $prefix = strtoupper($request->input('coupon_code_prefix'));
        // 모든 유저들과 가장 최신 그룹이름 가져오기
        $users = DB::table('users')->pluck('id');
        $groups = DB::table('groups')->max('group');
        // 만약 그룹이 없다면 'A'로 초기화
        if (!$groups){
            $groups = "A";
        }
        // 가져온 그룹이름 증감연산 ("Z" 라면 "AA"로 연산)
        else{
            ++$groups;
        }

//        echo $users;
//        echo $groups;

//        $coupon_gen = CouponCodeGenerator::generator($prefix,"S",$users);
        // 메모리 문제로 만개씩 나눠서 생성 및 insert
        for($i=0;$i<10;$i++){
            $data = CouponCodeGenerator::generate_coupons(10000,$prefix,$groups,$users);
            Coupon::insert($data);
        }
        // 그룹 insert
        $in_group = array('prefix' => $prefix, 'group' => $groups, 'coupon_num' => 100000);
        Group::create($in_group);

//        $coupon = Coupon::create($coupon_gen);
        // 생성 되면 홈으로 리다이랙트
        return redirect('/coupons')->with('success', 'Coupon is successfully saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        // 쿠폰 코드 조회 페이지 모든 유저 접근가능 (현재 빈값)
    public function show(Request $request)
    {
        // 빈페이지 출력
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
    // 쿠폰 그룹별 검색
    public function update(Request $request, $id)
    {
        //
        //
        // 입력받은 그룹이름을 대문자로
        $group = strtoupper($request->input('group'));
//        var_dump($group);
//
        // 그룹이름에 맞는 쿠폰들 불러와서 출력
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
    // 쿠폰 코드 조회 버튼 클릭
    public function destroy(Request $request,$id)
    {
//        //
        // 입력 받은 쿠폰 정합성 판단
        $validatedData = $request->validate([
            'coupon_code' => 'required|alpha_num|min:16|max:16',
        ]);
        // 쿠폰 값 대문자로
        $code = strtoupper($request->input('coupon_code'));
        // 입력 받은 쿠폰에 맞는 데이터 불러와서 출력
        $coupons = Coupon::where('code',$code)->first();
        $users = DB::table('users')->pluck('name','id')->all();

        return view('coupons.show', compact(['coupons','users']));
    }
}

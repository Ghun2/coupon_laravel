<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Charts\SampleChart;
use App\User;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
        // 쿠폰 테이블에서 그룹별 그룹이름과 유저들, 사용 쿠폰 갯수 가져옴 ( user name을 위해서 users 테이블 left조인
        $groups = DB::table('coupons')->select(DB::raw('`group`,user_id,users.name,count(*) as cnt'))
            ->where('useable',1)
            ->groupBy('group','user_id','useable')
            ->leftJoin('users','coupons.user_id','=','users.id')
            ->get();
        // 모든 유저 이름(차트 라벨용) 가져오기
        $user_name = User::pluck('name')->all();
        // 그룹이름
        $group_key = $groups->groupBy('group')->keys()->all();
        // 그룹이름을 키로 가지는 array
        $group_group = $groups->groupBy('group');
        // 새로운 차트 생성
        $chart = new SampleChart;
        // 차트 라벨은 모든 유저 이름
        $chart->labels($user_name);
        // 그룹별로 데이터 셋 생성
        for($i=0;$i<count($group_key);$i++){
            $chart->dataset($group_key[$i], 'line', $group_group[$group_key[$i]]->pluck('cnt')->all())->options([
                'color' => '#000000'
            ]);
        }


        return view('chart',compact('chart'));


    }
}

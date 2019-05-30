
<!-- index.blade.php -->

@extends('layout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>

    <div class="uper">
        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div><br />
        @endif
        <form action="{{{ url("coupons/index/{id}") }}}" method="get">
            <div class="form-group">
                @csrf
                <input type="text" class="form-control" name="group">
            </div>
            <button type="submit" class="btn btn-primary">그룹검색</button>
        </form>
        쿠폰 코드 리스트 페이지
        <table class="table table-striped">
            <thead>
            <tr>
                <td>ID</td>
                <td>Coupon Code</td>
                <td>Group</td>
                <td>User</td>
                <td>Usable</td>
                <td>Used_time</td>
                {{--                <td colspan="2">Action</td>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($coupons as $coupon)
                <tr>
                    <td>{{$coupon->id}}</td>
                    <td>{{$coupon->code}}</td>
                    <td>{{$coupon->group}}</td>
                    <td>{{$users[$coupon->user_id]}}</td>
                    <td>{{$coupon->useable == 1 ? "사용가능":"사용완료"}}</td>
                    <td>{{$coupon->useable == 1 ? "":$coupon->created_at}}</td>
                    {{--                    <td><a href="{{ route('coupons.edit',$coupon->id)}}" class="btn btn-primary">Edit</a></td>--}}
                    {{--                    <td>--}}
                    {{--                        <form action="{{ route('coupons.destroy', $coupon->id)}}" method="post">--}}
                    {{--                            @csrf--}}
                    {{--                            @method('DELETE')--}}
                    {{--                            <button class="btn btn-danger" type="submit">Delete</button>--}}
                    {{--                        </form>--}}
                    {{--                    </td>--}}
                </tr>
            @endforeach

            </tbody>
            {{ $coupons->links() }}
        </table>
        {{ $coupons->links() }}
        <div>
@endsection

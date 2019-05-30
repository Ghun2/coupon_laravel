<!-- show.blade.php -->

@extends('layout')

@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
    </style>
    <div class="card uper">
        <div class="card-header">
            쿠폰 코드 조회
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
            @endif
            <form method="get" action="{{ url('coupons/show/{id}') }}">
                <div class="form-group">
                    @csrf
                    <label for="code">쿠폰 번호 : </label>
                    <input type="text" class="form-control" name="coupon_code"/>
                </div>
                <button type="submit" class="btn btn-primary">Find Coupon</button>
            </form>
            @if ($coupons != null)
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
                        <tr>
                            <td>{{$coupons->id}}</td>
                            <td>{{$coupons->code}}</td>
                            <td>{{$coupons->group}}</td>
                            <td>{{$users[$coupons->user_id]}}</td>
                            <td>{{$coupons->useable == 1 ? "사용가능":"사용완료"}}</td>
                            <td>{{$coupons->useable == 1 ? "":$coupons->created_at}}</td>
                            {{--                    <td><a href="{{ route('coupons.edit',$coupon->id)}}" class="btn btn-primary">Edit</a></td>--}}
                            {{--                    <td>--}}
                            {{--                        <form action="{{ route('coupons.destroy', $coupon->id)}}" method="post">--}}
                            {{--                            @csrf--}}
                            {{--                            @method('DELETE')--}}
                            {{--                            <button class="btn btn-danger" type="submit">Delete</button>--}}
                            {{--                        </form>--}}
                            {{--                    </td>--}}
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

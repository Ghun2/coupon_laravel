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
                    <td>{{$coupon->name}}</td>
                    <td>{{$coupon->useable}}</td>
                    <td>{{$coupon->created_at}}</td>
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

<!-- index.blade.php -->

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
            <form method="post" action="{{ route('coupons.store') }}">
                <div class="form-group">
                    @csrf
                    <label for="code">쿠폰 번호 : </label>
                    <input type="text" class="form-control" name="coupon_code"/>
                </div>
                <button type="submit" class="btn btn-primary">Find Coupon</button>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('title', 'Something Went Wrong')
@section('content')
<section style="min-height: 60vh; display:flex; align-items:center; justify-content:center; text-align:center; padding: 2rem;">
    <div>
        <h1 style="font-size: 3rem;">Oops!</h1>
        <p>Something went wrong on our end. We're on it.</p>
        <a href="{{ url('/') }}" class="btn btn--primary">Back to Home</a>
    </div>
</section>
@endsection
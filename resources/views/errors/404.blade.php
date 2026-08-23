@extends('layouts.app')
@section('title', 'Page Not Found')
@section('content')
<section style="min-height: 60vh; display:flex; align-items:center; justify-content:center; text-align:center; padding: 2rem;">
    <div>
        <h1 style="font-size: 3rem;">Oops!</h1>
        <p>The page you are looking for could not be found.</p>
        <a href="{{ url('/') }}" class="btn btn--primary">Back to Home</a>
    </div>
</section>
@endsection

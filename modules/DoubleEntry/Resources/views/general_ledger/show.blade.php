@extends('layouts.admin')

@include($class->views['header'])

@section('content')
    @include($class->views['filter'])

    @include($class->views['content'])
@endsection

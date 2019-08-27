@extends('layouts.app')

@section('content')
    <inicio-admin-component :grupos="{{$groups}}"></inicio-admin-component>
@endsection
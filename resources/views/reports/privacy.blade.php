@extends('layouts.secondHeader')
@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12 mt-4">
        {!! html_entity_decode($privacy_policy) !!}
      </div>
    </div>
  </div>
@endsection
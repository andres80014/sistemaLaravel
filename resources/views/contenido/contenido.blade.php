@extends('principal')
@section('contenido')
    <template v-if="menu==0">

    </template>

    <template v-if="menu==1">
        <categoria></categoria>
    </template>

    <template v-if="menu==2">
        <articulo></articulo>
    </template>

    <template v-if="menu==6">
        <cliente><cliente>
    </template>

@endsection

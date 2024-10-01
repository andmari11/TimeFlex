<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

@props(['ref'=>"/", 'clase'=>""])

@php
    if(request()->is($ref)){
        $clase="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white";
    }
    else{
        $clase="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white";
    }
@endphp

<a href="{{$ref}}" class="{{$clase}}" aria-current="page">{{$slot}}</a>

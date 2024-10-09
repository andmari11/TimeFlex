<!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->

@props(['ref'=>"/", 'clase'=>""])

@php

    if(request()->is(trim($ref, '/')) or ($ref === '/' and request()->is('/'))){
        $clase="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white";
    }
    else{
        $clase="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white";
    }
@endphp

<a href="{{$ref}}" class="{{$clase}}" aria-current="page">{{$slot}}</a>

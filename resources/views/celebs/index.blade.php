@extends('layouts.app')

@section('content')
    <div class="container flex justify-between px-14 pt-16 pb-10 mt-4 mx-auto w-full">
        <h1 class="font-medium text-gray-500 text-4xl whitespace-nowrap">Favourite Celebs</h1>
        @if(Auth::check() && Auth::user()->is_admin)
            <button type="button" onclick="document.location='{{ route("celebs.create") }}'" class="flex items-center font-medium tracking-wide capitalize transition-colors duration-200 transform rounded-md border-2 border-gray-700 hover:border-gray-500">
                <span class="mx-2 whitespace-nowrap">{{ __('Create a new Celeb') }}</span>
            </button>
        @endif
    </div>
    <div class="container p-14 mx-auto">
        <div class="flex items-baseline justify-center">
            <div class="grid gap-8 mt-0 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($celebs as $celeb)
                    <a href={{ url("celebs/$celeb->id") }}>
                        <div class="w-full max-w-xs text-center">
                            <img class="object-contain object-center w-full h-60 mx-auto rounded-lg" src={{$celeb->photo}} alt="celeb_photo"/>

                            <div class="mt-2 flex">
                                <span class="text-lg font-medium text-gray-700 ">{{$celeb->name}}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex justify-center mb-4">
        {{$celebs->links()}}
    </div>
@endsection

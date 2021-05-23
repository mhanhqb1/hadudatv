@extends('layouts.app')

@section('content')
    <section class="max-w-4xl p-6 my-24 mx-auto bg-white rounded-md shadow-md">
        <h2 class="text-lg font-semibold text-gray-700 capitalize">Edit Celebrity</h2>

        <form method="POST" action="{{ route('celebs.update', $celeb->id) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700" for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{old('name', $celeb->name)}}" class="@error('name') is-invalid @enderror block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring">
                    @error('name')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-gray-700" for="date_of_birth">Date of Birth</label>
                    <input id="date_of_birth" name="date_of_birth" type="date" value="{{old('date_of_birth', $celeb->date_of_birth)}}" class="@error('date_of_birth') is-invalid @enderror block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('date_of_birth')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ $message }}</div>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="text-gray-700 dark:text-gray-200" for="photo">Photo URL</label>
                    <input id="photo" name="photo" type="url" value="{{old('photo', $celeb->photo)}}" class="@error('photo') is-invalid @enderror block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring">
                    @error('photo')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="flex flex-col mt-10">
                <div class="flex flex-row align-middle items-center">
                    <button type="submit" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Update</button>
                    <a class="text-gray-400 hover:text-gray-600 px-4 py-2" href="{{ route('celebs.show', $celeb) }}">Cancel</a>
                </div>
            </div>
        </form>
    </section>
@endsection

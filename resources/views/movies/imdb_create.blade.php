@extends('layouts.app')

@section('content')
<div class="container mx-auto flex px-5 py-16 md:flex-row flex-col items-start align-top">
    <form action="{{ route('movies.imdbCreate') }}" method="GET">
        <div class="form-group">
            <input class="form-control" name="s" value="{{ !empty($_GET['s']) ? $_GET['s'] : '' }}" placeholder="Nhap ten phim" />
        </div>
        <div class="form-group">
            <button type="submit">Search</button>
        </div>
    </form>
    @if(!empty($data))
    <ul style="display: block;">
        @foreach($data as $k => $v)
        <li style="display:flex; align-items:center;">
            <p>{{ $k + 1 }}</p>
            <img src="{{ $v['Poster'] }}" width="200px" />
            <p>{{ $v['Title'].' - '.$v['Year'] }}</p>
            <a href="{{ route('movies.imdbStore', $v['imdbID']) }}" target="_blank">Add</a>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection

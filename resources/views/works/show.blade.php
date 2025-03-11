@extends('layouts.app')

@section('title', $work->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">{{ $work->title }}</h1>
        <p class="text-gray-600 mb-4">{{ $work->description }}</p>
        @foreach($work->files as $file)
            @if($file->status === 'active')
                <embed src="{{ asset('storage/' . $file->file_path) }}" type="application/pdf" width="100%" height="600px" />
            @endif
        @endforeach
    </div>
</div>
@endsection
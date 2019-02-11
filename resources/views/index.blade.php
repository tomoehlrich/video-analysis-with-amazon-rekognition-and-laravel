@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="font-hairline mb-4">Amazon Rekognition Test</h1>

        <p class="mb-4"><a href="{{ url('/upload') }}" class="bg-teal-dark hover:bg-teal text-white font-bold py-2 px-4 rounded no-underline">Upload new video</a></p>

        <div class="border-teal p-8 border-t-12 bg-white mb-6 rounded-lg shadow-lg">
        @if($videos->isNotEmpty())
            <table class="table-auto">
            <tr>
                <th class="p-4 border-teal border-solid border-b-2 text-left">Video</th>
                <th class="p-4 border-teal border-solid border-b-2 text-left">Uploaded at</th>
                <th class="p-4 border-teal border-solid border-b-2 text-left">Analyzed</th>
                <th class="p-4 border-teal border-solid border-b-2"></th>
            </tr>
            @foreach($videos as $video)
                <tr>
                    <td class="p-4 text-left">{{ $video->original_name }}</td>
                    <td class="p-4 text-left">{{ $video->created_at }}</td>
                    <td class="p-4 text-left">{{ $video->analyzed ? $video->updated_at : '---' }}</td>
                    <td>
                        @if($video->analyzed)
                            <a href="{{ url('/results', ['id' => $video->id ]) }}" class="bg-teal-dark hover:bg-teal text-white font-bold py-2 px-4 rounded no-underline">Result</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </table>
        @else
            <p>No videos avaialble</p>
        @endif
        </div>
    </div>

@endsection

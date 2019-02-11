@extends('layouts.app')

@section('content')
    <div class="p-4">
        <h1 class="font-hairline mb-4">Amazon Rekognition Test</h1>
        <h2 class="font-hairline mb-4">{{ $video['original_name'] }}</h2>
        <p class="mb-4"><a href="{{ url('/') }}" class="bg-teal-dark hover:bg-teal text-white font-bold py-2 px-4 rounded no-underline">Back</a></p>

        <div class="border-teal p-8 border-t-12 bg-white mb-6 rounded-lg shadow-lg">
        @if(!empty($video['results']))
            <table class="table-auto">
            <tr>
                <th class="p-4 border-teal border-solid border-b-2 text-left">Timestamp</th>
                <th class="p-4 border-teal border-solid border-b-2 text-left">Label</th>
                <th class="p-4 border-teal border-solid border-b-2 text-left">Confidence</th>
            </tr>
            @foreach($video['results'] as $result)
                <tr>
                    <td class="p-4 text-left">{{ $result['Timestamp'] }}</td>
                    <td class="p-4 text-left">{{ $result['Label']['Name'] }}</td>
                    <td class="p-4 text-left">{{ $result['Label']['Confidence'] }}</td>
                </tr>
            @endforeach
            </table>
        @else
            <p>No results avaialble</p>
        @endif
        </div>
    </div>

@endsection

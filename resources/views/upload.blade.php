@extends('layouts.app')

@section('content')
<div class="container mx-auto h-full flex justify-center items-center">
    <div class="w-1/3">
        <h1 class="font-hairline mb-6 text-center">Amazon Rekognition Test</h1>

        <form action="/upload" method="post" enctype="multipart/form-data">
        @csrf
            <div class="border-teal p-8 border-t-12 bg-white mb-6 rounded-lg shadow-lg">

                @if (session('success'))
                    <div class="border-green p-4 text-green">
                        {{ session('success') }}
                    </div>
                @endif

                @if(count($errors) > 0)
                    <div class="border-red p-4 text-red">
                        Something went wrong<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="font-bold text-grey-darker block mb-2">Video</label>
                    <input type="file" name="file" class="block appearance-none w-full bg-white border border-grey-light hover:border-grey px-2 py-2 rounded shadow">
                </div>

                <div class="flex items-center justify-between">
                    <button class="bg-teal-dark hover:bg-teal text-white font-bold py-2 px-4 rounded">
                        Upload
                    </button>

                    <a href="{{ url('/') }}" class="bg-teal-dark hover:bg-teal text-white font-bold py-2 px-4 rounded no-underline">Go to index</a>

                </div>

            </div>
        </form>

    </div>
</div>
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2>Create Short URL</h2>
    </x-slot>

    <div style="padding: 20px;">
        @if($errors->any())
            <ul style="color: red;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('short-urls.store') }}">
            @csrf
            <p>
                <label>Original URL:</label><br>
                <input type="url" name="original_url" value="{{ old('original_url') }}" required size="60">
            </p>

            <button
                style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;"
                type="submit">Generate</button>
        </form>
    </div>
</x-app-layout>
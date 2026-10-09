<x-app-layout>
    <x-slot name="header">
        <h2>Generated Short URLs</h2>
    </x-slot>

    <div style="padding: 20px;">
        @if(session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif

        @if(!$user->isSuperAdmin())
            <p><a style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;"
                    href="{{ route('short-urls.create') }}">Generate Short URL</a></p>
        @endif

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Short URL</th>
                    <th>Original URL</th>
                    @if($user->isSuperAdmin())
                        <th>Company</th>
                    @endif
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shortUrls as $shortUrl)
                    <tr>
                        <td>{{ $shortUrl->id }}</td>
                        <td>
                            <a href="{{ url('/s/' . $shortUrl->short_code) }}" target="_blank">
                                {{ url('/s/' . $shortUrl->short_code) }}
                            </a>
                        </td>
                        <td>{{ $shortUrl->original_url }}</td>
                        @if($user->isSuperAdmin())
                            <td>{{ $shortUrl->company->name ?? '-' }}</td>
                        @endif
                        <td>{{ $shortUrl->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $user->isSuperAdmin() ? 5 : 4 }}">No short urls found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
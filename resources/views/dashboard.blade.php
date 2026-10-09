<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard</h2>
    </x-slot>

    <div style="padding: 20px;">
        <p>Welcome, <strong>{{ $user->name }}</strong></p>
        <p>Your role: <strong>{{ $user->role }}</strong></p>

        @if($user->company)
            <p>Company: <strong>{{ $user->company->name }}</strong></p>
        @else
            <p>Company: <em>None</em></p>
        @endif

        <hr style="margin: 20px 0;">

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            {{-- Invitations --}}
            @if($user->isSuperAdmin() || $user->isAdmin())
                <a href="{{ route('invitations.index') }}"
                   style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;">
                    View Invitations
                </a>
            @endif

            @if($user->isSuperAdmin())
                <a href="{{ route('invitations.create-client') }}"
                   style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;">
                    Invite New Client/Company
                </a>
            @endif

            @if($user->isAdmin())
                <a href="{{ route('invitations.create-member') }}"
                   style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;">
                    Invite New Team Member
                </a>
            @endif

            {{-- Short URLs --}}
            @if($user->isSuperAdmin())
                <a href="{{ route('short-urls.index') }}"
                   style="display: inline-block; padding: 8px 16px; background: #059669; color: #fff; text-decoration: none; border-radius: 4px;">
                    View All Short URLs
                </a>
            @elseif($user->isAdmin() || $user->isMember())
                <a href="{{ route('short-urls.index') }}"
                   style="display: inline-block; padding: 8px 16px; background: #059669; color: #fff; text-decoration: none; border-radius: 4px;">
                    View Short URLs
                </a>
                <a href="{{ route('short-urls.create') }}"
                   style="display: inline-block; padding: 8px 16px; background: #059669; color: #fff; text-decoration: none; border-radius: 4px;">
                    Generate Short URL
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
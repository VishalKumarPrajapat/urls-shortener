<x-app-layout>
    <x-slot name="header">
        <h2>Invitations</h2>
    </x-slot>

    <div style="padding: 20px;">
        @if(session('status'))
            <p style="color: green;">{{ session('status') }}</p>
        @endif

        @if($user->isSuperAdmin())
            <p>
                <a style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;" href="{{ route('invitations.create-client') }}">Invite New Client/Company</a>
            </p>
        @endif

        @if($user->isAdmin())
            <p>
                <a style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;" href="{{ route('invitations.create-member') }}">Invite New Team Member</a>
            </p>
        @endif

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invitations as $invitation)
                    @php $url = route('invitations.accept.show', $invitation->id); @endphp
                    <tr>
                        <td>{{ $invitation->id }}</td>
                        <td>{{ $invitation->company_name ?? ($invitation->company->name ?? '-') }}</td>
                        <td>{{ $invitation->email }}</td>
                        <td>{{ $invitation->role }}</td>
                        <td>
                            <button type="button" onclick="copyInvite('{{ $url }}', this)">Copy</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No invitations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function copyInvite(text, btn) {
            navigator.clipboard.writeText(text).then(function () {
                const original = btn.innerText;
                btn.innerText = 'Copied!';
                setTimeout(function () { btn.innerText = original; }, 1500);
            }).catch(function () {
                alert('Copy failed. Please copy manually.');
            });
        }
    </script>
</x-app-layout>
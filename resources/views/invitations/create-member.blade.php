<x-app-layout>
    <x-slot name="header">
        <h2>Invite New Team Member</h2>
    </x-slot>

    <div style="padding: 20px;">
        @if($errors->any())
            <ul style="color: red;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('invitations.store-member') }}">
            @csrf

            <p>
                <label>Email:</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </p>

            <p>
                <label>Role:</label><br>
                <select name="role" required>
                    <option value="">-- Select --</option>
                    <option value="Admin" @selected(old('role') === 'Admin')>Admin</option>
                    <option value="Member" @selected(old('role') === 'Member')>Member</option>
                </select>
            </p>

            <button style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;" type="submit">Send Invitation</button>
        </form>
    </div>
</x-app-layout>
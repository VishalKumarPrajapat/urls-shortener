<x-guest-layout>
    <h2>Accept Invitation</h2>

    <p>Email: <strong>{{ $invitation->email }}</strong></p>
    <p>Role: <strong>{{ $invitation->role }}</strong></p>

    @if($invitation->company_name)
        <p>Company: <strong>{{ $invitation->company_name }}</strong></p>
    @elseif($invitation->company)
        <p>Company: <strong>{{ $invitation->company->name }}</strong></p>
    @endif

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('invitations.accept.store', $invitation->id) }}">
        @csrf

        <p>
            <label>Name:</label><br>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </p>

        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>

        <p>
            <label>Confirm Password:</label><br>
            <input type="password" name="password_confirmation" required>
        </p>

        <button style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;" type="submit">Create Account</button>
    </form>
</x-guest-layout>
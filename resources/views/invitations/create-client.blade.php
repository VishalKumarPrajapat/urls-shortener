<x-app-layout>
    <x-slot name="header">
        <h2>Invite New Client</h2>
    </x-slot>

    <div style="padding: 20px;">
        @if($errors->any())
            <ul style="color: red;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('invitations.store-client') }}">
            @csrf

            <p>
                <label>Client/Company Name:</label><br>
                <input type="text" name="company_name" value="{{ old('company_name') }}" required>
            </p>

            <p>
                <label>Email:</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </p>

            <button style="display: inline-block; padding: 8px 16px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 4px;" type="submit">Send Invitation</button>
        </form>
    </div>
</x-app-layout>
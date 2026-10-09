<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    /* Invite New Client */
    public function createClient(Request $request)
    {
        $user = $request->user();
        if (! $user->isSuperAdmin()) {
            abort(403);
        }
        return view('invitations.create-client');
    }

    public function storeClient(Request $request)
    {
        $user = $request->user();

        if (! $user->isSuperAdmin()) {
            abort(403);
        }
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        Invitation::create([
            'company_id' => null,
            'company_name' => $data['company_name'],
            'invited_by' => $user->id,
            'email' => $data['email'],
            'role' => 'Admin',
        ]);
        return redirect()->route('invitations.index')->with('status', 'Client invitation sent to ' . $data['email']);
    }

    /* Invite New Team Member */
    public function createMember(Request $request)
    {
        $user = $request->user();
        if (! $user->isAdmin()) {
            abort(403);
        }
        return view('invitations.create-member');
    }


    public function storeMember(Request $request)
    {
        $user = $request->user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:Admin,Member',
        ]);

        Invitation::create([
            'company_id' => $user->company_id,
            'company_name' => null,
            'invited_by' => $user->id,
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        return redirect()->route('invitations.index')
            ->with('status', 'Team invitation sent to ' . $data['email']);
    }

    /* List Invitations */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isSuperAdmin()) {
            $invitations = Invitation::orderByDesc('id')->get();
        } elseif ($user->isAdmin()) {
            $invitations = Invitation::where('company_id', $user->company_id)->orderByDesc('id')->get();
        } else {
            abort(403);
        }
        return view('invitations.index', compact('invitations', 'user'));
    }

    /* Accept Invitation */
    public function show(string $token)
    {
        $invitation = Invitation::findOrFail($token);
        return view('invitations.accept', compact('invitation'));
    }

    public function store(Request $request, string $token)
    {
        $invitation = Invitation::findOrFail($token);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (User::where('email', $invitation->email)->exists()) {
            return back()->withErrors(['email' => 'A user with this email already exists.']);
        }
 
        if (is_null($invitation->company_id) && $invitation->company_name) {
            $company = Company::create(['name' => $invitation->company_name]);
            $companyId = $company->id;
        } else {
            $companyId = $invitation->company_id;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $invitation->email,
            'password' => Hash::make($data['password']),
            'role' => $invitation->role,
            'company_id' => $companyId,
        ]);

        $invitation->delete();
        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
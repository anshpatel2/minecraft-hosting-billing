<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get(); // Get all users for statistics
        return view('admin.users.index', compact('users'));
    }

    public function datatable(Request $request)
    {
        logger('DataTable request received', [
            'is_ajax' => $request->ajax(),
            'user_count' => User::count(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        try {
            // Allow both AJAX and non-AJAX requests for testing
            $users = User::with('roles')->select('users.*');
            
            $dataTable = DataTables::of($users)
                ->addColumn('roles_display', function ($user) {
                    if ($user->roles->count() > 0) {
                        return $user->roles->map(function ($role) {
                            return '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-1">' . $role->name . '</span>';
                        })->implode('');
                    }
                    return '<span class="text-gray-400">No role assigned</span>';
                })
                ->addColumn('status_display', function ($user) {
                    if ($user->email_verified_at) {
                        return '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Verified</span>';
                    }
                    return '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Unverified</span>';
                })
                ->addColumn('actions', function ($user) {
                    $actions = '<div class="flex space-x-2">';
                    $actions .= '<a href="' . route('admin.users.show', $user) . '" class="text-indigo-600 hover:text-indigo-900" title="View"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                    $actions .= '<a href="' . route('admin.users.edit', $user) . '" class="text-yellow-600 hover:text-yellow-900" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>';
                    
                    if (!$user->email_verified_at) {
                        $actions .= '<form method="POST" action="' . route('admin.users.verify', $user) . '" class="inline"><input type="hidden" name="_token" value="' . csrf_token() . '"><button type="submit" class="text-green-600 hover:text-green-900" title="Verify" onclick="return confirm(\'Verify this user?\')"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button></form>';
                    }
                    
                    if ($user->id !== Auth::id()) {
                        $actions .= '<form method="POST" action="' . route('admin.users.destroy', $user) . '" class="inline"><input type="hidden" name="_token" value="' . csrf_token() . '"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm(\'Are you sure you want to delete this user?\')"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button></form>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->addColumn('created_formatted', function ($user) {
                    return $user->created_at->format('M d, Y');
                })
                ->rawColumns(['roles_display', 'status_display', 'actions'])
                ->make(true);
                
            return $dataTable;
            
        } catch (\Exception $e) {
            logger('DataTable error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'email_verified' => 'boolean',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        // Assign roles if provided
        if (isset($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        return redirect()->route('admin.users')->with('success', "User {$user->name} created successfully!");
    }

    public function verify(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'User is already verified.');
        }

        $user->markEmailAsVerified();

        return redirect()->back()->with('success', "User {$user->email} has been verified successfully!");
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        // Update basic user info
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->update([
                'password' => bcrypt($validated['password']),
            ]);
        }

        // Update roles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function resetPassword(User $user)
    {
        // Generate a new random password
        $newPassword = Str::random(12);
        
        // Update the user's password
        $user->update([
            'password' => bcrypt($newPassword),
        ]);

        // You could also send an email notification here
        // notification('Password has been reset for user: ' . $user->email);

        return response()->json([
            'success' => true,
            'message' => "Password reset successfully for {$user->name}",
            'new_password' => $newPassword
        ]);
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}

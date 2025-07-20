<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'user';
    public $is_verified = false;
    
    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:user,admin',
        'is_verified' => 'boolean'
    ];

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterStatus === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($this->filterStatus === 'unverified') {
            $query->whereNull('email_verified_at');
        }

        $users = $query->withCount(['orders', 'servers'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.user-manager', compact('users'));
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function showEditForm($userId)
    {
        $user = User::findOrFail($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_verified = !is_null($user->email_verified_at);
        
        // Clear password fields for editing
        $this->password = '';
        $this->password_confirmation = '';
        
        $this->showEditModal = true;
    }

    public function showDeleteForm($userId)
    {
        $this->userId = $userId;
        $this->showDeleteModal = true;
    }

    public function createUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'email_verified_at' => $this->is_verified ? now() : null
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'User created successfully!');
    }

    public function updateUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:user,admin',
            'is_verified' => 'boolean'
        ];

        // Only validate password if it's provided
        if (!empty($this->password)) {
            $rules['password'] = 'min:8|confirmed';
        }

        $this->validate($rules);

        $user = User::findOrFail($this->userId);
        
        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'email_verified_at' => $this->is_verified ? now() : null
        ];

        // Only update password if provided
        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }

        $user->update($updateData);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('message', 'User updated successfully!');
    }

    public function deleteUser()
    {
        try {
            $user = User::findOrFail($this->userId);
            
            // Check if user has active orders or servers
            $activeOrders = $user->orders()->where('status', 'active')->count();
            $serverCount = $user->servers()->count();
            
            if ($activeOrders > 0) {
                session()->flash('error', "Cannot delete user with {$activeOrders} active orders! Please cancel orders first.");
                $this->showDeleteModal = false;
                return;
            }
            
            if ($serverCount > 0) {
                session()->flash('error', "Cannot delete user with {$serverCount} servers! Please remove servers first.");
                $this->showDeleteModal = false;
                return;
            }
            
            $userName = $user->name;
            $user->delete();
            $this->showDeleteModal = false;
            $this->resetForm();
            
            session()->flash('message', "User '{$userName}' deleted successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    public function toggleUserVerification($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $newStatus = $user->email_verified_at ? null : now();
            $user->update(['email_verified_at' => $newStatus]);
            
            $statusText = $newStatus ? 'verified' : 'unverified';
            session()->flash('message', "User '{$user->name}' marked as {$statusText}!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update verification status: ' . $e->getMessage());
        }
    }

    public function impersonateUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Store admin user ID in session for returning later
        session(['impersonating_from' => Auth::id()]);
        
        // Log in as the user
        Auth::login($user);
        
        return redirect()->route('dashboard')->with('message', 'Now impersonating: ' . $user->name);
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->is_verified = false;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Server;
use App\Models\Order;
use App\Models\User;
use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class ServerManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showViewModal = false;
    public $showDeleteModal = false;
    
    public $serverId;
    public $user_id;
    public $plan_id;
    public $order_id;
    public $name;
    public $server_id;
    public $status = 'offline';
    public $minecraft_version = '1.20.1';
    public $server_type = 'vanilla';
    public $ip_address;
    public $port = 25565;
    public $settings = [];
    
    public $search = '';
    public $filterStatus = '';
    public $filterUser = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'plan_id' => 'required|exists:plans,id',
        'order_id' => 'nullable|exists:orders,id',
        'name' => 'required|string|max:255',
        'server_id' => 'nullable|string|max:255',
        'status' => 'required|in:active,inactive,suspended,maintenance',
        'minecraft_version' => 'required|string',
        'server_type' => 'required|string',
        'ip_address' => 'required|ip',
        'port' => 'required|integer|min:1|max:65535',
        'settings' => 'array'
    ];

    public function render()
    {
        $query = Server::with(['user', 'plan', 'order']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('server_id', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        $servers = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);
        $users = User::orderBy('name')->get();
        $orders = Order::with(['user', 'plan'])->get();

        return view('livewire.admin.server-manager', compact('servers', 'users', 'orders'));
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

    public function showViewForm($serverId)
    {
        $this->serverId = $serverId;
        $this->showViewModal = true;
    }

    public function showDeleteForm($serverId)
    {
        $this->serverId = $serverId;
        $this->showDeleteModal = true;
    }

    public function createServer()
    {
        $this->validate();

        Server::create([
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'order_id' => $this->order_id,
            'name' => $this->name,
            'server_id' => $this->server_id,
            'status' => $this->status,
            'minecraft_version' => $this->minecraft_version,
            'server_type' => $this->server_type,
            'ip_address' => $this->ip_address,
            'port' => $this->port,
            'settings' => $this->settings
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Server created successfully!');
    }

    public function updateServerStatus($serverId, $status)
    {
        try {
            $server = Server::findOrFail($serverId);
            $oldStatus = $server->status;
            $server->update(['status' => $status]);
            
            session()->flash('message', "Server '{$server->name}' status changed from {$oldStatus} to {$status}!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update server status: ' . $e->getMessage());
        }
    }

    public function deleteServer()
    {
        try {
            $server = Server::findOrFail($this->serverId);
            
            // Check if server can be deleted
            if ($server->status === 'active') {
                session()->flash('error', 'Cannot delete active servers! Stop the server first.');
                $this->showDeleteModal = false;
                return;
            }
            
            $serverName = $server->name;
            $server->delete();
            $this->showDeleteModal = false;
            $this->resetForm();
            
            session()->flash('message', "Server '{$serverName}' deleted successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete server: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    public function restartServer($serverId)
    {
        try {
            $server = Server::findOrFail($serverId);
            $server->update(['status' => 'maintenance']);
            session()->flash('message', "Server '{$server->name}' restart initiated!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to restart server: ' . $e->getMessage());
        }
    }

    public function startServer($serverId)
    {
        $this->updateServerStatus($serverId, 'active');
    }

    public function stopServer($serverId)
    {
        $this->updateServerStatus($serverId, 'inactive');
    }

    private function resetForm()
    {
        $this->serverId = null;
        $this->user_id = '';
        $this->plan_id = '';
        $this->order_id = '';
        $this->name = '';
        $this->server_id = '';
        $this->status = 'inactive';
        $this->minecraft_version = '1.20.1';
        $this->server_type = 'vanilla';
        $this->ip_address = '';
        $this->port = 25565;
        $this->settings = [];
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showViewModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterUser()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}

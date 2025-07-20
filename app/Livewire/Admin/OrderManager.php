<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showViewModal = false;
    public $showDeleteModal = false;
    
    public $orderId;
    public $user_id;
    public $plan_id;
    public $status = 'pending';
    public $total_amount;
    public $notes;
    
    public $search = '';
    public $filterStatus = '';
    public $filterPlan = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'plan_id' => 'required|exists:plans,id',
        'status' => 'required|in:pending,active,suspended,cancelled',
        'total_amount' => 'required|numeric|min:0',
        'notes' => 'nullable|string'
    ];

    public function render()
    {
        $query = Order::with(['user', 'plan']);

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function ($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })->orWhereHas('plan', function ($planQuery) {
                    $planQuery->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterPlan) {
            $query->where('plan_id', $this->filterPlan);
        }

        $orders = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $users = User::orderBy('name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();
        $allPlans = Plan::orderBy('name')->get();

        return view('livewire.admin.order-manager', compact('orders', 'users', 'plans', 'allPlans'));
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

    public function showViewForm($orderId)
    {
        $this->orderId = $orderId;
        $this->showViewModal = true;
    }

    public function showDeleteForm($orderId)
    {
        $this->orderId = $orderId;
        $this->showDeleteModal = true;
    }

    public function createOrder()
    {
        $this->validate();

        Order::create([
            'user_id' => $this->user_id,
            'plan_id' => $this->plan_id,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'notes' => $this->notes
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Order created successfully!');
    }

    public function updateOrderStatus($orderId, $status)
    {
        try {
            $order = Order::findOrFail($orderId);
            $oldStatus = $order->status;
            
            $order->update(['status' => $status]);
            
            // Log activity
            \App\Models\Activity::log(
                $order->user_id,
                'order_status_changed',
                'Order Status Updated',
                "Order #{$order->id} status changed from {$oldStatus} to {$status}",
                'edit'
            );
            
            session()->flash('message', "Order #{$order->id} status updated to " . ucfirst($status) . " successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    private function createServerForOrder($order)
    {
        // Create server logic here
        // This would typically create a new server record
        // and potentially provision the actual server
    }

    public function deleteOrder()
    {
        try {
            $order = Order::findOrFail($this->orderId);
            
            // Check if order can be deleted (optional business logic)
            if ($order->status === 'active') {
                session()->flash('error', 'Cannot delete active orders! Please cancel the order first.');
                $this->showDeleteModal = false;
                return;
            }

            $orderNumber = $order->order_number ?? "#" . $order->id;
            $order->delete();
            
            $this->showDeleteModal = false;
            $this->resetForm();
            session()->flash('message', "Order {$orderNumber} deleted successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete order: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    public function updatedPlanId()
    {
        if ($this->plan_id) {
            $plan = Plan::find($this->plan_id);
            if ($plan) {
                $this->total_amount = $plan->price;
            }
        }
    }

    private function resetForm()
    {
        $this->orderId = null;
        $this->user_id = '';
        $this->plan_id = '';
        $this->status = 'pending';
        $this->total_amount = '';
        $this->notes = '';
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

    public function updatedFilterPlan()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}

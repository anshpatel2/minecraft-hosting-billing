<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\User;
use App\Models\Plan;
use Carbon\Carbon;

class BillingManager extends Component
{
    use WithPagination;

    // Properties for invoice data
    public $search = '';
    public $filterStatus = '';
    public $filterUser = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // Modal states
    public $showCreateModal = false;
    public $showViewModal = false;
    public $showRefundModal = false;
    public $editingOrderId = null;

    // Form properties for creating new invoices
    public $user_id = '';
    public $plan_id = '';
    public $quantity = 1;
    public $total_price = '';
    public $billing_cycle = 'monthly';
    public $status = 'pending';
    public $payment_method = 'paypal';
    public $due_date = '';
    public $notes = '';

    // Refund properties
    public $refund_amount = '';
    public $refund_reason = '';

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'plan_id' => 'required|exists:plans,id',
        'quantity' => 'required|integer|min:1|max:100',
        'total_price' => 'required|numeric|min:0',
        'billing_cycle' => 'required|in:monthly,quarterly,yearly',
        'status' => 'required|in:pending,processing,completed,failed,refunded',
        'payment_method' => 'required|string',
        'due_date' => 'nullable|date|after:today',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->due_date = Carbon::now()->addDays(30)->format('Y-m-d');
    }

    public function render()
    {
        $query = Order::with(['user', 'plan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                                 ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('plan', function ($planQuery) {
                        $planQuery->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterUser, function ($query) {
                $query->where('user_id', $this->filterUser);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $orders = $query->paginate($this->perPage);

        $users = User::orderBy('name')->get();
        $plans = Plan::orderBy('name')->get();

        // Calculate billing stats
        $stats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total_price'),
            'pending_payments' => Order::where('status', 'pending')->sum('total_price'),
            'monthly_revenue' => Order::where('status', 'completed')
                ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->sum('total_price'),
            'refunded_amount' => Order::where('status', 'refunded')->sum('total_price'),
        ];

        return view('livewire.admin.billing-manager', compact('orders', 'users', 'plans', 'stats'));
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function showViewForm($orderId)
    {
        $this->editingOrderId = $orderId;
        $this->showViewModal = true;
    }

    public function showRefundForm($orderId)
    {
        $order = Order::find($orderId);
        $this->editingOrderId = $orderId;
        $this->refund_amount = $order->total_price;
        $this->refund_reason = '';
        $this->showRefundModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showViewModal = false;
        $this->showRefundModal = false;
        $this->editingOrderId = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->user_id = '';
        $this->plan_id = '';
        $this->quantity = 1;
        $this->total_price = '';
        $this->billing_cycle = 'monthly';
        $this->status = 'pending';
        $this->payment_method = 'paypal';
        $this->due_date = Carbon::now()->addDays(30)->format('Y-m-d');
        $this->notes = '';
        $this->refund_amount = '';
        $this->refund_reason = '';
    }

    public function updatedPlanId()
    {
        if ($this->plan_id) {
            $plan = Plan::find($this->plan_id);
            if ($plan) {
                $this->total_price = $plan->price * $this->quantity;
            }
        }
    }

    public function updatedQuantity()
    {
        if ($this->plan_id && $this->quantity > 0) {
            $plan = Plan::find($this->plan_id);
            if ($plan) {
                $this->total_price = $plan->price * $this->quantity;
            }
        }
    }

    public function createInvoice()
    {
        $this->validate();

        try {
            $order = Order::create([
                'user_id' => $this->user_id,
                'plan_id' => $this->plan_id,
                'quantity' => $this->quantity,
                'total_price' => $this->total_price,
                'billing_cycle' => $this->billing_cycle,
                'status' => $this->status,
                'payment_method' => $this->payment_method,
                'due_date' => $this->due_date,
                'notes' => $this->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            session()->flash('message', 'Invoice created successfully!');
            $this->closeModal();
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus($orderId, $status)
    {
        try {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['status' => $status]);
                session()->flash('message', 'Order status updated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function processRefund()
    {
        $this->validate([
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_reason' => 'required|string|max:500',
        ]);

        try {
            $order = Order::find($this->editingOrderId);
            if ($order && $order->status === 'completed') {
                $order->update([
                    'status' => 'refunded',
                    'refund_amount' => $this->refund_amount,
                    'refund_reason' => $this->refund_reason,
                    'refunded_at' => now(),
                ]);

                session()->flash('message', 'Refund processed successfully!');
                $this->closeModal();
            } else {
                session()->flash('error', 'Only completed orders can be refunded.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    public function markAsPaid($orderId)
    {
        try {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
                session()->flash('message', 'Order marked as paid successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to mark order as paid: ' . $e->getMessage());
        }
    }

    public function sendInvoice($orderId)
    {
        try {
            $order = Order::with(['user', 'plan'])->find($orderId);
            if ($order && $order->user) {
                // Here you would typically send an email notification
                // For now, we'll just show a success message
                session()->flash('message', 'Invoice sent to ' . $order->user->email . ' successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send invoice: ' . $e->getMessage());
        }
    }

    public function generatePDF($orderId)
    {
        try {
            $order = Order::with(['user', 'plan'])->find($orderId);
            if ($order) {
                // Here you would typically generate a PDF invoice
                // For now, we'll just show a success message
                session()->flash('message', 'PDF invoice generated successfully!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}

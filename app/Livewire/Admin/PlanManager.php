<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class PlanManager extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    public $planId;
    public $name;
    public $description;
    public $price;
    public $billing_cycle = 'monthly';
    public $ram_gb;
    public $storage_gb;
    public $max_players;
    public $features = [];
    public $is_active = true;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'billing_cycle' => 'required|string',
        'ram_gb' => 'required|integer|min:1',
        'storage_gb' => 'required|integer|min:1',
        'max_players' => 'required|integer|min:1',
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function render()
    {
        $query = Plan::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('billing_cycle', 'like', '%' . $this->search . '%')
                  ->orWhere('price', 'like', '%' . $this->search . '%');
            });
        }
        
        $plans = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        return view('livewire.admin.plan-manager', compact('plans'));
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

    public function showEditForm($planId)
    {
        $plan = Plan::findOrFail($planId);
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->price = $plan->price;
        $this->billing_cycle = $plan->billing_cycle;
        $this->ram_gb = $plan->ram_gb;
        $this->storage_gb = $plan->storage_gb;
        $this->max_players = $plan->max_players;
        $this->features = $plan->features ?? [];
        $this->is_active = $plan->is_active;
        
        $this->showEditModal = true;
    }

    public function showDeleteForm($planId)
    {
        $this->planId = $planId;
        $this->showDeleteModal = true;
    }

    public function createPlan()
    {
        $this->validate();

        Plan::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'billing_cycle' => $this->billing_cycle,
            'ram_gb' => $this->ram_gb,
            'storage_gb' => $this->storage_gb,
            'max_players' => $this->max_players,
            'features' => $this->features,
            'is_active' => $this->is_active
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'Plan created successfully!');
    }

    public function updatePlan()
    {
        $this->validate();

        $plan = Plan::findOrFail($this->planId);
        $plan->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'billing_cycle' => $this->billing_cycle,
            'ram_gb' => $this->ram_gb,
            'storage_gb' => $this->storage_gb,
            'max_players' => $this->max_players,
            'features' => $this->features,
            'is_active' => $this->is_active
        ]);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('message', 'Plan updated successfully!');
    }

    public function deletePlan()
    {
        try {
            $plan = Plan::findOrFail($this->planId);
            
            // Check if plan has active orders
            if ($plan->orders()->where('status', 'active')->exists()) {
                session()->flash('error', 'Cannot delete plan with active orders! Please deactivate the plan instead.');
                $this->showDeleteModal = false;
                return;
            }
            
            // Check if plan has any orders at all
            $orderCount = $plan->orders()->count();
            if ($orderCount > 0) {
                session()->flash('error', "Cannot delete plan with {$orderCount} existing orders. Consider deactivating the plan instead.");
                $this->showDeleteModal = false;
                return;
            }
            
            $planName = $plan->name;
            $plan->delete();
            $this->showDeleteModal = false;
            $this->resetForm();
            
            session()->flash('message', "Plan '{$planName}' deleted successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete plan: ' . $e->getMessage());
            $this->showDeleteModal = false;
        }
    }

    public function addFeature()
    {
        $this->features[] = '';
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function togglePlanStatus($planId)
    {
        try {
            $plan = Plan::findOrFail($planId);
            $oldStatus = $plan->is_active ? 'active' : 'inactive';
            $newStatus = !$plan->is_active;
            
            $plan->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus ? 'activated' : 'deactivated';
            session()->flash('message', "Plan '{$plan->name}' {$statusText} successfully!");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update plan status: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->planId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->billing_cycle = 'monthly';
        $this->ram_gb = '';
        $this->storage_gb = '';
        $this->max_players = '';
        $this->features = [];
        $this->is_active = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}

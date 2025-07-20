<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index');
    }

    public function datatable(Request $request)
    {
        $query = Plan::withCount(['orders'])
            ->select(['plans.*']);

        return DataTables::of($query)
            ->addColumn('status_badge', function ($plan) {
                $color = $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                $text = $plan->is_active ? 'Active' : 'Inactive';
                return "<span class='px-2 py-1 text-xs font-medium rounded-full {$color}'>{$text}</span>";
            })
            ->addColumn('formatted_price', function ($plan) {
                return '$' . number_format($plan->price, 2) . '/' . $plan->billing_cycle;
            })
            ->addColumn('specs', function ($plan) {
                return "
                    <div class='text-sm'>
                        <div>{$plan->ram_gb}GB RAM</div>
                        <div>{$plan->storage_gb}GB Storage</div>
                        <div>{$plan->max_players} Players</div>
                    </div>
                ";
            })
            ->addColumn('actions', function ($plan) {
                $statusAction = $plan->is_active 
                    ? '<button onclick="toggleStatus(' . $plan->id . ')" class="btn-sm btn-warning" title="Deactivate"><i class="fas fa-pause"></i></button>'
                    : '<button onclick="toggleStatus(' . $plan->id . ')" class="btn-sm btn-success" title="Activate"><i class="fas fa-play"></i></button>';
                
                return '
                    <div class="flex items-center space-x-2">
                        <button onclick="viewPlan(' . $plan->id . ')" class="btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </button>
                        ' . $statusAction . '
                        <button onclick="editPlan(' . $plan->id . ')" class="btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePlan(' . $plan->id . ')" class="btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->addColumn('orders_count_display', function ($plan) {
                return $plan->orders_count ?? 0;
            })
            ->rawColumns(['status_badge', 'specs', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'ram_gb' => 'required|integer|min:1',
            'storage_gb' => 'required|integer|min:1',
            'max_players' => 'required|integer|min:1',
            'features' => 'array',
            'is_active' => 'boolean'
        ]);

        $plan = Plan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plan created successfully!',
            'plan' => $plan
        ]);
    }

    public function show($id)
    {
        $plan = Plan::withCount(['orders'])->findOrFail($id);
        return response()->json(['plan' => $plan]);
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return response()->json(['plan' => $plan]);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'ram_gb' => 'required|integer|min:1',
            'storage_gb' => 'required|integer|min:1',
            'max_players' => 'required|integer|min:1',
            'features' => 'array',
            'is_active' => 'boolean'
        ]);

        $plan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plan updated successfully!',
            'plan' => $plan
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);

        $status = $plan->is_active ? 'activated' : 'deactivated';
        
        return response()->json([
            'success' => true,
            'message' => "Plan {$status} successfully!"
        ]);
    }

    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            
            $activeOrders = $plan->orders()->where('status', 'active')->count();
            if ($activeOrders > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete plan with {$activeOrders} active orders!"
                ], 400);
            }

            $plan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Plan deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete plan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        $plans = Plan::where('is_active', true)->select('id', 'name', 'price')->get();
        return response()->json($plans);
    }
}

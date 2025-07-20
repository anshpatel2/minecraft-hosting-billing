<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function datatable(Request $request)
    {
        $query = Order::with(['user', 'plan'])
            ->select(['orders.*']);

        return DataTables::of($query)
            ->addColumn('user_name', function ($order) {
                return $order->user ? $order->user->name : 'N/A';
            })
            ->addColumn('user_email', function ($order) {
                return $order->user ? $order->user->email : 'N/A';
            })
            ->addColumn('plan_name', function ($order) {
                return $order->plan ? $order->plan->name : 'N/A';
            })
            ->addColumn('status_badge', function ($order) {
                $colors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'active' => 'bg-green-100 text-green-800',
                    'suspended' => 'bg-red-100 text-red-800',
                    'cancelled' => 'bg-gray-100 text-gray-800'
                ];
                $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-800';
                return "<span class='px-2 py-1 text-xs font-medium rounded-full {$color}'>" . ucfirst($order->status) . "</span>";
            })
            ->addColumn('actions', function ($order) {
                return '
                    <div class="flex items-center space-x-2">
                        <button onclick="viewOrder(' . $order->id . ')" class="btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="relative">
                            <button onclick="toggleStatus(' . $order->id . ')" class="btn-sm btn-secondary">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                        <button onclick="editOrder(' . $order->id . ')" class="btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteOrder(' . $order->id . ')" class="btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->addColumn('formatted_amount', function ($order) {
                return '$' . number_format($order->total_price ?? $order->amount ?? 0, 2);
            })
            ->addColumn('formatted_date', function ($order) {
                return $order->created_at->format('M d, Y');
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('user_email', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('email', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('plan_name', function ($query, $keyword) {
                $query->whereHas('plan', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['status_badge', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders.create', compact('users', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,active,suspended,cancelled',
            'notes' => 'nullable|string'
        ]);

        $order = Order::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully!',
            'order' => $order
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'plan'])->findOrFail($id);
        return response()->json(['order' => $order]);
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $users = User::orderBy('name')->get();
        $plans = Plan::orderBy('name')->get();
        
        return response()->json([
            'order' => $order,
            'users' => $users,
            'plans' => $plans
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,active,suspended,cancelled',
            'notes' => 'nullable|string'
        ]);

        $order->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!',
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,active,suspended,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            if ($order->status === 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete active orders! Please cancel the order first.'
                ], 400);
            }

            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: ' . $e->getMessage()
            ], 500);
        }
    }
}

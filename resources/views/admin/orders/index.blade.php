<x-modern-layout title="Orders Management">
    <div class="modern-card">
        <div class="card-header">
            <h1 class="card-title">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                Orders Management
            </h1>
            <button onclick="openCreateModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create Order
            </button>
        </div>

        <div class="card-body">
            <div class="overflow-x-auto">
                <table id="ordersTable" class="dataTable display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ORDER ID</th>
                            <th>CUSTOMER</th>
                            <th>PLAN</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="orderModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" style="color: #374151;">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="modalTitle">Create Order</span>
                </h2>
                <button onclick="closeModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    @csrf
                    <input type="hidden" id="orderId" name="order_id">
                    
                    <div class="form-group">
                        <label for="user_id" style="color: #374151; font-weight: 500;">Customer</label>
                        <select id="user_id" name="user_id" class="form-control" required style="color: #374151; background-color: #fff;">
                            <option value="" style="color: #6b7280;">Select Customer</option>
                            <!-- Users will be loaded via AJAX -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="plan_id" style="color: #374151; font-weight: 500;">Plan</label>
                        <select id="plan_id" name="plan_id" class="form-control" required style="color: #374151; background-color: #fff;">
                            <option value="" style="color: #6b7280;">Select Plan</option>
                            <!-- Plans will be loaded via AJAX -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount" style="color: #374151; font-weight: 500;">Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control" step="0.01" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="status" style="color: #374151; font-weight: 500;">Status</label>
                        <select id="status" name="status" class="form-control" required style="color: #374151; background-color: #fff;">
                            <option value="pending" style="color: #374151;">Pending</option>
                            <option value="active" style="color: #374151;">Active</option>
                            <option value="cancelled" style="color: #374151;">Cancelled</option>
                            <option value="completed" style="color: #374151;">Completed</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="server_ip" style="color: #374151; font-weight: 500;">Server IP</label>
                        <input type="text" id="server_ip" name="server_ip" class="form-control" style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="server_port" style="color: #374151; font-weight: 500;">Server Port</label>
                        <input type="number" id="server_port" name="server_port" class="form-control" style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span id="submitText">Create Order</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.orders.datatable") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { 
                        data: 'user_name', 
                        name: 'user_name', 
                        orderable: false,
                        render: function(data, type, row) {
                            return data + '<br><small class="text-gray-500">' + (row.user_email || '') + '</small>';
                        }
                    },
                    { data: 'plan_name', name: 'plan_name', orderable: false },
                    { data: 'formatted_amount', name: 'amount' },
                    { data: 'status_badge', name: 'status', orderable: false },
                    { data: 'formatted_date', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']],
                language: {
                    processing: '<div class="loading">Loading...</div>',
                    emptyTable: "No orders found",
                    zeroRecords: "No matching orders found"
                }
            });

            // Load users and plans for dropdowns
            loadUsers();
            loadPlans();
        });

        function loadUsers() {
            $.get('/admin/users/list', function(users) {
                const select = $('#user_id');
                select.find('option:not(:first)').remove();
                users.forEach(function(user) {
                    select.append(`<option value="${user.id}" style="color: #374151;">${user.name} (${user.email})</option>`);
                });
            });
        }

        function loadPlans() {
            $.get('/admin/plans/list', function(plans) {
                const select = $('#plan_id');
                select.find('option:not(:first)').remove();
                plans.forEach(function(plan) {
                    select.append(`<option value="${plan.id}" data-price="${plan.price}" style="color: #374151;">${plan.name} - $${plan.price}</option>`);
                });
            });
        }

        $('#plan_id').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const price = selectedOption.data('price');
            if (price) {
                $('#amount').val(price);
            }
        });

        function openCreateModal() {
            $('#orderModal').removeClass('hidden').addClass('show');
            $('#modalTitle').text('Create Order');
            $('#submitText').text('Create Order');
            $('#orderForm')[0].reset();
            $('#orderId').val('');
        }

        function closeModal() {
            $('#orderModal').addClass('hidden').removeClass('show');
        }

        function editOrder(id) {
            $('#orderModal').removeClass('hidden').addClass('show');
            $('#modalTitle').text('Edit Order');
            $('#submitText').text('Update Order');
            $('#orderId').val(id);
            
            // Load order data
            $.get(`/admin/orders/${id}/edit`, function(data) {
                const order = data.order || data;
                $('#user_id').val(order.user_id);
                $('#plan_id').val(order.plan_id);
                $('#amount').val(order.amount);
                $('#status').val(order.status);
                $('#server_ip').val(order.server_ip || '');
                $('#server_port').val(order.server_port || '');
            }).fail(function() {
                showAlert('Error loading order data', 'error');
            });
        }

        function deleteOrder(id) {
            if (confirm('Are you sure you want to delete this order?')) {
                $.post(`/admin/orders/${id}`, {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function(response) {
                    $('#ordersTable').DataTable().ajax.reload();
                    showAlert('Order deleted successfully!', 'success');
                }).fail(function() {
                    showAlert('Error deleting order', 'error');
                });
            }
        }

        function viewOrder(id) {
            window.open(`/admin/orders/${id}`, '_blank');
        }

        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const orderId = $('#orderId').val();
            const url = orderId ? `/admin/orders/${orderId}` : '{{ route("admin.orders.store") }}';
            const method = orderId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function(response) {
                    closeModal();
                    $('#ordersTable').DataTable().ajax.reload();
                    showAlert('Order saved successfully!', 'success');
                },
                error: function() {
                    showAlert('Error saving order', 'error');
                }
            });
        });

        function showAlert(message, type) {
            // Remove existing alerts
            $('.alert').remove();
            
            // Create alert element
            const alertClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const alertDiv = `
                <div class="alert fixed top-4 right-4 px-6 py-4 text-white rounded-lg shadow-lg z-50 ${alertClass}">
                    ${message}
                </div>
            `;
            $('body').append(alertDiv);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                $('.alert').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }

        // Close modal when clicking outside
        $(document).on('click', '.modal-backdrop', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
    @endpush
</x-modern-layout>

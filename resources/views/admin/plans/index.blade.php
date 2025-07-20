<x-modern-layout title="Plans Management">
    <div class="modern-card">
        <div class="card-header">
            <h1 class="card-title">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                Plans Management
            </h1>
            <button onclick="openCreateModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create Plan
            </button>
        </div>

        <div class="card-body">
            <div class="overflow-x-auto">
                <table id="plansTable" class="dataTable display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>PRICE</th>
                            <th>SPECS</th>
                            <th>ORDERS</th>
                            <th>STATUS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="planModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" style="color: #374151;">
                    <i class="fas fa-box"></i>
                    <span id="modalTitle">Create Plan</span>
                </h2>
                <button onclick="closeModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="planForm">
                    @csrf
                    <input type="hidden" id="planId" name="plan_id">
                    
                    <div class="form-group">
                        <label for="name" style="color: #374151; font-weight: 500;">Plan Name</label>
                        <input type="text" id="name" name="name" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="description" style="color: #374151; font-weight: 500;">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" style="color: #374151; background-color: #fff;"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price" style="color: #374151; font-weight: 500;">Price</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="billing_cycle" style="color: #374151; font-weight: 500;">Billing Cycle</label>
                        <select id="billing_cycle" name="billing_cycle" class="form-control" required style="color: #374151; background-color: #fff;">
                            <option value="monthly" style="color: #374151;">Monthly</option>
                            <option value="quarterly" style="color: #374151;">Quarterly</option>
                            <option value="yearly" style="color: #374151;">Yearly</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ram_gb" style="color: #374151; font-weight: 500;">RAM (GB)</label>
                        <input type="number" id="ram_gb" name="ram_gb" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="storage_gb" style="color: #374151; font-weight: 500;">Storage (GB)</label>
                        <input type="number" id="storage_gb" name="storage_gb" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="cpu" style="color: #374151; font-weight: 500;">CPU Cores</label>
                        <input type="number" id="cpu" name="cpu" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="max_players" style="color: #374151; font-weight: 500;">Max Players</label>
                        <input type="number" id="max_players" name="max_players" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label for="is_active" style="color: #374151; font-weight: 500;">Active</label>
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span id="submitText">Create Plan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#plansTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.plans.datatable") }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'formatted_price', name: 'price' },
                    { data: 'specs', name: 'specs', orderable: false },
                    { 
                        data: 'orders_count_display', 
                        name: 'orders_count',
                        render: function(data, type, row) {
                            return data || 0;
                        }
                    },
                    { data: 'status_badge', name: 'is_active', orderable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']],
                language: {
                    processing: '<div class="loading">Loading...</div>',
                    emptyTable: "No plans found",
                    zeroRecords: "No matching plans found"
                }
            });
        });

        function openCreateModal() {
            $('#planModal').removeClass('hidden').addClass('show');
            $('#modalTitle').text('Create Plan');
            $('#submitText').text('Create Plan');
            $('#planForm')[0].reset();
            $('#planId').val('');
            $('#is_active').prop('checked', true);
        }

        function closeModal() {
            $('#planModal').addClass('hidden').removeClass('show');
        }

        function editPlan(id) {
            $('#planModal').removeClass('hidden').addClass('show');
            $('#modalTitle').text('Edit Plan');
            $('#submitText').text('Update Plan');
            $('#planId').val(id);
            
            // Load plan data
            $.get(`/admin/plans/${id}/edit`, function(data) {
                if (data.plan) {
                    const plan = data.plan;
                    $('#name').val(plan.name || '');
                    $('#description').val(plan.description || '');
                    $('#price').val(plan.price || '');
                    $('#billing_cycle').val(plan.billing_cycle || 'monthly');
                    $('#ram_gb').val(plan.ram_gb || '');
                    $('#storage_gb').val(plan.storage_gb || '');
                    $('#cpu').val(plan.cpu || '');
                    $('#max_players').val(plan.max_players || '');
                    $('#is_active').prop('checked', plan.is_active == 1);
                } else {
                    // Handle direct plan object
                    $('#name').val(data.name || '');
                    $('#description').val(data.description || '');
                    $('#price').val(data.price || '');
                    $('#billing_cycle').val(data.billing_cycle || 'monthly');
                    $('#ram_gb').val(data.ram_gb || '');
                    $('#storage_gb').val(data.storage_gb || '');
                    $('#cpu').val(data.cpu || '');
                    $('#max_players').val(data.max_players || '');
                    $('#is_active').prop('checked', data.is_active == 1);
                }
            }).fail(function() {
                showAlert('Error loading plan data', 'error');
            });
        }

        function deletePlan(id) {
            if (confirm('Are you sure you want to delete this plan?')) {
                $.ajax({
                    url: `/admin/plans/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#plansTable').DataTable().ajax.reload();
                        showAlert('Plan deleted successfully!', 'success');
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            showAlert(xhr.responseJSON.message, 'error');
                        } else {
                            showAlert('Error deleting plan', 'error');
                        }
                    }
                });
            }
        }

        function toggleStatus(id) {
            $.ajax({
                url: `/admin/plans/${id}/toggle-status`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#plansTable').DataTable().ajax.reload();
                    showAlert('Plan status updated!', 'success');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        showAlert(xhr.responseJSON.message, 'error');
                    } else {
                        showAlert('Error updating status', 'error');
                    }
                }
            });
        }

        $('#planForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const planId = $('#planId').val();
            const url = planId ? `/admin/plans/${planId}` : '{{ route("admin.plans.store") }}';
            
            if (planId) {
                formData.append('_method', 'PUT');
            }
            
            // Handle checkbox
            formData.set('is_active', $('#is_active').is(':checked') ? 1 : 0);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    closeModal();
                    $('#plansTable').DataTable().ajax.reload();
                    showAlert('Plan saved successfully!', 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMessage = 'Validation errors:\n';
                        Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                            errorMessage += '- ' + xhr.responseJSON.errors[key][0] + '\n';
                        });
                        showAlert(errorMessage, 'error');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        showAlert(xhr.responseJSON.message, 'error');
                    } else {
                        showAlert('Error saving plan', 'error');
                    }
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

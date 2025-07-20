<x-modern-layout title="Users Management">
    <div class="modern-card">
        <div class="card-header">
            <h1 class="card-title">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                Users Management
            </h1>
            <button onclick="openCreateModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create User
            </button>
        </div>

        <div class="card-body">
            <div class="overflow-x-auto">
                <table id="usersTable" class="dataTable display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>ROLES</th>
                            <th>STATUS</th>
                            <th>JOINED</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div id="userModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" style="color: #374151;">
                    <i class="fas fa-user"></i>
                    <span id="modalTitle">Create User</span>
                </h2>
                <button onclick="closeModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    @csrf
                    <input type="hidden" id="userId" name="user_id">
                    
                    <div class="form-group">
                        <label for="name" style="color: #374151; font-weight: 500;">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="email" style="color: #374151; font-weight: 500;">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required style="color: #374151; background-color: #fff;">
                    </div>

                    <div class="form-group">
                        <label for="password" style="color: #374151; font-weight: 500;">Password</label>
                        <input type="password" id="password" name="password" class="form-control" style="color: #374151; background-color: #fff;">
                        <small class="form-help" style="color: #6b7280;">Leave blank to keep current password</small>
                    </div>

                    <div class="form-group">
                        <label for="role" style="color: #374151; font-weight: 500;">Role</label>
                        <select id="role" name="role" class="form-control" required style="color: #374151; background-color: #fff;">
                            <option value="user" style="color: #374151;">User</option>
                            <option value="admin" style="color: #374151;">Admin</option>
                        </select>
                    </div>

                    <div class="form-checkbox">
                        <input type="checkbox" id="email_verified" name="email_verified" value="1">
                        <label for="email_verified" style="color: #374151; font-weight: 500;">Email Verified</label>
                    </div>

                    <div class="form-actions">
                        <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span id="submitText">Create User</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("admin.users.datatable") }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'roles_display', name: 'roles_display', orderable: false },
                    { data: 'status_display', name: 'status_display', orderable: false },
                    { data: 'created_formatted', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']],
                language: {
                    processing: '<div class="loading">Loading...</div>',
                    emptyTable: "No users found",
                    zeroRecords: "No matching users found"
                }
            });
        });

        function openCreateModal() {
            $('#userModal').removeClass('hidden');
            $('#modalTitle').text('Create User');
            $('#submitText').text('Create User');
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#password').attr('required', true);
        }

        function closeModal() {
            $('#userModal').addClass('hidden');
        }

        function editUser(id) {
            $('#userModal').removeClass('hidden');
            $('#modalTitle').text('Edit User');
            $('#submitText').text('Update User');
            $('#userId').val(id);
            $('#password').attr('required', false);
            
            // Load user data
            $.get(`/admin/users/${id}/edit`, function(data) {
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#role').val(data.role);
                $('#email_verified').prop('checked', data.email_verified_at !== null);
            });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.post(`/admin/users/${id}`, {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function(response) {
                    $('#usersTable').DataTable().ajax.reload();
                    showAlert('User deleted successfully!', 'success');
                }).fail(function() {
                    showAlert('Error deleting user', 'error');
                });
            }
        }

        function verifyUser(id) {
            $.post(`/admin/users/${id}/verify`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(response) {
                $('#usersTable').DataTable().ajax.reload();
                showAlert('User verified successfully!', 'success');
            }).fail(function() {
                showAlert('Error verifying user', 'error');
            });
        }

        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const userId = $('#userId').val();
            const url = userId ? `/admin/users/${userId}` : '{{ route("admin.users.store") }}';
            const method = userId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function(response) {
                    closeModal();
                    $('#usersTable').DataTable().ajax.reload();
                    showAlert('User saved successfully!', 'success');
                },
                error: function() {
                    showAlert('Error saving user', 'error');
                }
            });
        });

        function showAlert(message, type) {
            const alertDiv = `<div class="alert alert-${type}">${message}</div>`;
            $('body').prepend(alertDiv);
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 3000);
        }
    </script>
    @endpush
</x-modern-layout>

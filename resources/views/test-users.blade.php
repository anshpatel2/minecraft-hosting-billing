<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Users Table</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Users Management Test</h1>
        
        <!-- Filter Panel -->
        <div class="mb-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium mb-4">Advanced Filters</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div>
                    <label for="filter-name" class="block text-sm font-medium mb-2">Filter by Name</label>
                    <input type="text" id="filter-name" placeholder="Enter name..." 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="filter-email" class="block text-sm font-medium mb-2">Filter by Email</label>
                    <input type="email" id="filter-email" placeholder="Enter email..." 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="clear-filters" class="block text-sm font-medium mb-2">&nbsp;</label>
                    <button id="clear-filters" 
                            class="w-full px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table id="users-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-2" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-indigo-600 hover:text-indigo-900 mr-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium mb-2">No users found</p>
                                <p>Get started by creating a new user.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log('Initializing DataTables...');
            
            // Initialize DataTable
            var table = $('#users-table').DataTable({
                responsive: true,
                processing: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[0, 'desc']],
                
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy mr-2"></i>Copy',
                        className: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel mr-2"></i>Excel',
                        className: 'bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf mr-2"></i>PDF',
                        className: 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-2"></i>Print',
                        className: 'bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded'
                    }
                ],
                
                columnDefs: [
                    {
                        targets: [4],
                        orderable: false,
                        searchable: false
                    }
                ],
                
                language: {
                    search: "Search users:",
                    lengthMenu: "Show _MENU_ users per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    infoEmpty: "No users found",
                    infoFiltered: "(filtered from _MAX_ total users)",
                    zeroRecords: "No matching users found"
                }
            });
            
            console.log('DataTable initialized successfully');
            
            // Enhanced filtering system
            function clearFilters() {
                $.fn.dataTable.ext.search = [];
            }
            
            let filterTimeout;
            function setupFilter(selector, columnIndex) {
                $(selector).on('keyup change', function() {
                    clearTimeout(filterTimeout);
                    const value = this.value.toLowerCase();
                    
                    filterTimeout = setTimeout(() => {
                        if (value) {
                            clearFilters();
                            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                                if (settings.nTable.id !== 'users-table') return true;
                                const row = table.row(dataIndex).node();
                                const cellText = $(row).find(`td:eq(${columnIndex})`).text().toLowerCase();
                                return cellText.includes(value);
                            });
                        } else {
                            clearFilters();
                        }
                        table.draw();
                    }, 300);
                });
            }
            
            // Apply filters
            setupFilter('#filter-name', 1);
            setupFilter('#filter-email', 2);
            
            // Clear filters
            $('#clear-filters').on('click', function() {
                $('input[id^="filter-"]').val('');
                clearFilters();
                table.search('').columns().search('').draw();
                console.log('Filters cleared');
            });
            
            console.log('All filters initialized');
        });
    </script>
</body>
</html>

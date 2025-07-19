/**
 * Custom DataTables Implementation for Admin Panel
 * Matches site theme and provides enhanced functionality
 */

class AdminDataTable {
    constructor(selector, options = {}) {
        this.selector = selector;
        this.table = null;
        this.defaultOptions = {
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'asc']],
            dom: '<"admin-table-controls"<"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6"<"flex items-center gap-4"l<"search-container">><"export-buttons"B>>>rtip',
            language: {
                search: "",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "No entries found",
                infoFiltered: "(filtered from _MAX_ total entries)",
                zeroRecords: "No matching records found",
                emptyTable: "No data available",
                paginate: {
                    first: "First",
                    last: "Last", 
                    next: "Next",
                    previous: "Previous"
                }
            },
            drawCallback: function() {
                // Add custom styling after each redraw
                this.api().columns.adjust().responsive.recalc();
            }
        };
        
        this.options = { ...this.defaultOptions, ...options };
        this.init();
    }

    init() {
        // Add custom search container
        this.addCustomSearch();
        
        // Initialize DataTable
        this.table = $(this.selector).DataTable(this.options);
        
        // Apply custom styling
        this.applyCustomStyling();
        
        // Add event listeners
        this.addEventListeners();
    }

    addCustomSearch() {
        // Custom search will be added via DOM manipulation
        const customSearchHtml = `
            <div class="search-wrapper relative">
                <input type="text" id="customSearch" class="custom-search-input" placeholder="Search users...">
                <div class="search-icon">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        `;
        
        // This will be injected after DataTable initialization
        this.customSearchHtml = customSearchHtml;
    }

    applyCustomStyling() {
        // Hide default search
        $('.dataTables_filter').hide();
        
        // Add custom search
        $('.search-container').html(this.customSearchHtml);
        
        // Style length selector
        $('.dataTables_length select').addClass('admin-select');
        
        // Style pagination
        $('.dataTables_paginate').addClass('admin-pagination');
        
        // Add table classes
        $(this.selector).addClass('admin-table');
        
        // Style export buttons
        $('.dt-buttons .dt-button').each(function() {
            const $btn = $(this);
            const text = $btn.text().toLowerCase();
            
            $btn.removeClass().addClass('dt-button btn-admin');
            
            switch(text) {
                case 'copy':
                    $btn.addClass('btn-admin-primary');
                    break;
                case 'csv':
                case 'excel':
                    $btn.addClass('btn-admin-success');
                    break;
                case 'pdf':
                    $btn.addClass('btn-admin-danger');
                    break;
                case 'print':
                    $btn.addClass('btn-admin-secondary');
                    break;
                default:
                    $btn.addClass('btn-admin-primary');
            }
        });
    }

    addEventListeners() {
        const self = this;
        
        // Custom search functionality
        $('#customSearch').on('keyup', function() {
            self.table.search(this.value).draw();
        });

        // Add role filter functionality
        this.addRoleFilter();
        
        // Add status filter functionality  
        this.addStatusFilter();
    }

    addRoleFilter() {
        const self = this;
        
        // Create role filter dropdown
        const roles = this.getUniqueRoles();
        const roleFilterHtml = `
            <select id="roleFilter" class="admin-select ml-2">
                <option value="">All Roles</option>
                ${roles.map(role => `<option value="${role}">${role}</option>`).join('')}
            </select>
        `;
        
        $('.search-container').append(roleFilterHtml);
        
        $('#roleFilter').on('change', function() {
            const selectedRole = $(this).val();
            if (selectedRole) {
                self.table.column(2).search(selectedRole, true, false).draw();
            } else {
                self.table.column(2).search('').draw();
            }
        });
    }

    addStatusFilter() {
        const self = this;
        
        const statusFilterHtml = `
            <select id="statusFilter" class="admin-select ml-2">
                <option value="">All Status</option>
                <option value="Verified">Verified</option>
                <option value="Unverified">Unverified</option>
            </select>
        `;
        
        $('.search-container').append(statusFilterHtml);
        
        $('#statusFilter').on('change', function() {
            const selectedStatus = $(this).val();
            if (selectedStatus) {
                self.table.column(3).search(selectedStatus, true, false).draw();
            } else {
                self.table.column(3).search('').draw();
            }
        });
    }

    getUniqueRoles() {
        const roles = new Set();
        
        $(this.selector + ' tbody tr').each(function() {
            const roleCell = $(this).find('td:eq(2)');
            const roleText = roleCell.text().trim();
            
            if (roleText && roleText !== 'No role assigned') {
                // Extract role names from badges
                roleCell.find('.role-badge').each(function() {
                    const role = $(this).text().trim();
                    if (role) roles.add(role);
                });
            }
        });
        
        return Array.from(roles).sort();
    }

    // Public methods
    refresh() {
        this.table.ajax.reload();
    }
    
    getTable() {
        return this.table;
    }

    search(term) {
        this.table.search(term).draw();
    }

    export(type) {
        this.table.button('.' + type).trigger();
    }

    destroy() {
        if (this.table) {
            this.table.destroy();
            this.table = null;
        }
    }
}

// Utility functions for admin panel
const AdminUtils = {
    // Show loading state
    showLoading: function(element) {
        $(element).addClass('loading');
    },

    // Hide loading state
    hideLoading: function(element) {
        $(element).removeClass('loading');
    },

    // Show notification
    showNotification: function(message, type = 'info') {
        const notification = `
            <div class="notification notification-${type} fade-in">
                <div class="notification-content">
                    <span>${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            </div>
        `;
        
        $('body').append(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            $('.notification').fadeOut(() => {
                $('.notification').remove();
            });
        }, 5000);
    },

    // Confirm dialog
    confirm: function(message, callback) {
        if (window.confirm(message)) {
            callback();
        }
    },

    // Format date
    formatDate: function(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    },

    // Format status badge
    formatStatus: function(isVerified) {
        if (isVerified) {
            return '<span class="status-badge status-verified">Verified</span>';
        } else {
            return '<span class="status-badge status-unverified">Unverified</span>';
        }
    },

    // Format role badge
    formatRole: function(role) {
        const roleClass = `role-${role.toLowerCase()}`;
        return `<span class="role-badge ${roleClass}">${role}</span>`;
    }
};

// Initialize when document is ready
$(document).ready(function() {
    // Add smooth scrolling
    $('html').css('scroll-behavior', 'smooth');
    
    // Add fade-in animation to cards
    $('.admin-card').addClass('fade-in');
    
    // Enhanced form validation
    $('form').on('submit', function() {
        const form = $(this);
        AdminUtils.showLoading(form);
        
        // Re-enable after 3 seconds to prevent permanent disable
        setTimeout(() => {
            AdminUtils.hideLoading(form);
        }, 3000);
    });
});

// Export for global use
window.AdminDataTable = AdminDataTable;
window.AdminUtils = AdminUtils;

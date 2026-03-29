// Global variables
let tasks = [];
let filteredTasks = [];
let myFilteredTasks = [];
const currentUser = 'Nguyễn Văn A';
let currentPage = 1;
let myCurrentPage = 1;
const tasksPerPage = 10;

// Bootstrap modal instances
let taskModal, completeModal, viewModal, deleteModal;

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modals
    taskModal = new bootstrap.Modal(document.getElementById('taskModal'));
    completeModal = new bootstrap.Modal(document.getElementById('completeModal'));
    viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    // Set today's date as default for date inputs
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('taskStartDate').value = today;
    document.getElementById('taskEndDate').value = today;

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            
            // Create or toggle overlay
            let overlay = document.querySelector('.sidebar-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
            overlay.classList.toggle('show');
        });
    }

    // Initialize with empty filters
    applyFilters();
    applyMyFilters();

    // Load sample data
    loadSampleData();
});

// Load sample data
function loadSampleData() {
    const sampleTasks = [
        {
            id: 'task-1',
            content: 'Hoàn thành báo cáo tháng',
            description: 'Tổng hợp số liệu và viết báo cáo tổng kết tháng 1',
            startTime: '08:00',
            endTime: '17:00',
            startDate: new Date().toISOString(),
            endDate: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000).toISOString(),
            assignee: 'Nguyễn Văn A',
            createdBy: 'Quản lý',
            status: 'Chờ xử lý',
            attachmentName: null,
            completionNote: null
        },
        {
            id: 'task-2',
            content: 'Họp team dự án',
            description: 'Thảo luận tiến độ và phân công công việc tuần tới',
            startTime: '14:00',
            endTime: '16:00',
            startDate: new Date().toISOString(),
            endDate: new Date().toISOString(),
            assignee: 'Trần Thị B',
            createdBy: 'Quản lý',
            status: 'Chờ xử lý',
            attachmentName: null,
            completionNote: null
        },
        {
            id: 'task-3',
            content: 'Kiểm tra hệ thống',
            description: 'Kiểm tra và bảo trì hệ thống định kỳ',
            startTime: '09:00',
            endTime: '12:00',
            startDate: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString(),
            endDate: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString(),
            assignee: 'Lê Văn C',
            createdBy: 'Quản lý',
            status: 'Đã hoàn thành',
            attachmentName: null,
            completionNote: 'Đã hoàn thành kiểm tra, hệ thống hoạt động bình thường'
        }
    ];
    
    tasks = sampleTasks;
    applyFilters();
    applyMyFilters();
}

// Tab switching
function switchTab(tabName) {
    // Update nav links
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.classList.remove('active');
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });

    // Update page title
    let title = '';
    let tabElement = null;
    
    switch(tabName) {
        case 'tasks':
            title = 'Quản lý công việc';
            tabElement = document.getElementById('tasksTab');
            break;
        case 'myTasks':
            title = 'Công việc của tôi';
            tabElement = document.getElementById('myTasksTab');
            applyMyFilters(); // Refresh my tasks when switching to tab
            break;
        case 'calendar':
            title = 'Lịch trình làm việc';
            tabElement = document.getElementById('calendarTab');
            renderCalendar();
            break;
    }

    if (tabElement) {
        tabElement.classList.add('active');
    }
    
    document.getElementById('pageTitle').textContent = title;

    // Close sidebar on mobile after selection
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('show');
    }
}

// Date filter handling - Tasks Management
function handleDateFilterChange() {
    const selectedFilter = document.querySelector('input[name="dateFilter"]:checked');
    const customDateRange = document.getElementById('customDateRange');
    
    if (selectedFilter && selectedFilter.value === 'custom') {
        customDateRange.classList.remove('d-none');
    } else {
        customDateRange.classList.add('d-none');
    }
    
    applyFilters();
}

// Date filter handling - My Tasks
function handleMyDateFilterChange() {
    const selectedFilter = document.querySelector('input[name="myDateFilter"]:checked');
    const customDateRange = document.getElementById('myCustomDateRange');
    
    if (selectedFilter && selectedFilter.value === 'custom') {
        customDateRange.classList.remove('d-none');
    } else {
        customDateRange.classList.add('d-none');
    }
    
    applyMyFilters();
}

// Apply filters for tasks management
function applyFilters() {
    let filtered = [...tasks];
    
    // Text search
    const searchText = document.getElementById('searchText').value.toLowerCase().trim();
    if (searchText) {
        filtered = filtered.filter(task => 
            task.content.toLowerCase().includes(searchText) ||
            task.description.toLowerCase().includes(searchText)
        );
    }
    
    // Date filter
    const dateFilter = document.querySelector('input[name="dateFilter"]:checked');
    if (dateFilter) {
        filtered = filterByDate(filtered, dateFilter.value, 'fromDate', 'toDate');
    }
    
    // Assignee filter
    const assigneeFilter = document.getElementById('assigneeFilter').value;
    if (assigneeFilter !== 'all') {
        filtered = filtered.filter(task => task.assignee === assigneeFilter);
    }
    
    // Status filter
    const statusFilter = document.getElementById('statusFilter').value;
    if (statusFilter !== 'all') {
        filtered = filtered.filter(task => task.status === statusFilter);
    }
    
    filteredTasks = filtered;
    currentPage = 1;
    renderTasksTable();
}

// Apply filters for my tasks
function applyMyFilters() {
    // First filter by current user
    let filtered = tasks.filter(task => task.assignee === currentUser);
    
    // Text search
    const searchText = document.getElementById('mySearchText').value.toLowerCase().trim();
    if (searchText) {
        filtered = filtered.filter(task => 
            task.content.toLowerCase().includes(searchText) ||
            task.description.toLowerCase().includes(searchText)
        );
    }
    
    // Date filter
    const dateFilter = document.querySelector('input[name="myDateFilter"]:checked');
    if (dateFilter) {
        filtered = filterByDate(filtered, dateFilter.value, 'myFromDate', 'myToDate');
    }
    
    // Status filter
    const statusFilter = document.getElementById('myStatusFilter').value;
    if (statusFilter !== 'all') {
        filtered = filtered.filter(task => task.status === statusFilter);
    }
    
    myFilteredTasks = filtered;
    myCurrentPage = 1;
    renderMyTasksTable();
}

// Filter tasks by date
function filterByDate(tasks, filterType, fromDateId, toDateId) {
    if (filterType === 'all' || !filterType) {
        return tasks;
    }
    
    const now = new Date();
    let start, end;
    
    switch(filterType) {
        case 'yesterday':
            start = new Date(now);
            start.setDate(start.getDate() - 1);
            start.setHours(0, 0, 0, 0);
            end = new Date(start);
            end.setHours(23, 59, 59, 999);
            break;
        case 'today':
            start = new Date(now);
            start.setHours(0, 0, 0, 0);
            end = new Date(start);
            end.setHours(23, 59, 59, 999);
            break;
        case 'tomorrow':
            start = new Date(now);
            start.setDate(start.getDate() + 1);
            start.setHours(0, 0, 0, 0);
            end = new Date(start);
            end.setHours(23, 59, 59, 999);
            break;
        case 'thisWeek':
            start = new Date(now);
            const day = start.getDay();
            const diff = start.getDate() - day + (day === 0 ? -6 : 1); // Monday
            start.setDate(diff);
            start.setHours(0, 0, 0, 0);
            end = new Date(start);
            end.setDate(end.getDate() + 6);
            end.setHours(23, 59, 59, 999);
            break;
        case 'custom':
            const fromDate = document.getElementById(fromDateId).value;
            const toDate = document.getElementById(toDateId).value;
            if (fromDate && toDate) {
                start = new Date(fromDate);
                start.setHours(0, 0, 0, 0);
                end = new Date(toDate);
                end.setHours(23, 59, 59, 999);
            } else {
                return tasks;
            }
            break;
        default:
            return tasks;
    }
    
    return tasks.filter(task => {
        const taskStart = new Date(task.startDate);
        const taskEnd = new Date(task.endDate);
        return (taskStart >= start && taskStart <= end) ||
               (taskEnd >= start && taskEnd <= end) ||
               (taskStart <= start && taskEnd >= end);
    });
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchText').value = '';
    document.getElementById('assigneeFilter').value = 'all';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('fromDate').value = '';
    document.getElementById('toDate').value = '';
    
    // Uncheck all date filter radio buttons
    document.querySelectorAll('input[name="dateFilter"]').forEach(radio => {
        radio.checked = false;
    });
    
    document.getElementById('customDateRange').classList.add('d-none');
    applyFilters();
}

// Clear my tasks filters
function clearMyFilters() {
    document.getElementById('mySearchText').value = '';
    document.getElementById('myStatusFilter').value = 'all';
    document.getElementById('myFromDate').value = '';
    document.getElementById('myToDate').value = '';
    
    // Uncheck all date filter radio buttons
    document.querySelectorAll('input[name="myDateFilter"]').forEach(radio => {
        radio.checked = false;
    });
    
    document.getElementById('myCustomDateRange').classList.add('d-none');
    applyMyFilters();
}

// Render tasks table
function renderTasksTable() {
    const tbody = document.getElementById('tasksTableBody');
    
    if (filteredTasks.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-5">
                    <h6 class="text-muted">Không có công việc nào</h6>
                    <p class="text-muted small">Nhấn "Thêm mới" để tạo công việc đầu tiên</p>
                </td>
            </tr>
        `;
        document.getElementById('tasksPagination').innerHTML = '';
        return;
    }
    
    // Pagination
    const startIndex = (currentPage - 1) * tasksPerPage;
    const endIndex = startIndex + tasksPerPage;
    const paginatedTasks = filteredTasks.slice(startIndex, endIndex);
    
    tbody.innerHTML = paginatedTasks.map(task => `
        <tr>
            <td>
                <div class="fw-medium">${escapeHtml(task.content)}</div>
                ${task.description ? `<div class="text-muted small mt-1">${escapeHtml(task.description)}</div>` : ''}
            </td>
            <td>${formatTime(task.startTime, task.endTime)}</td>
            <td>
                <div>${formatDate(task.startDate)}</div>
                <div class="text-muted small">đến ${formatDate(task.endDate)}</div>
            </td>
            <td>${escapeHtml(task.assignee)}</td>
            <td><span class="badge ${getStatusBadgeClass(task.status)}">${task.status}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-outline-primary" onclick="editTask('${task.id}')" title="Sửa">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="openDeleteDialog('${task.id}')" title="Xóa">
                        <i class="bi bi-trash"></i>
                    </button>
                    ${task.status !== 'Đã hoàn thành' ? `
                        <button class="btn btn-sm btn-outline-success" onclick="openCompleteDialog('${task.id}')" title="Hoàn thành">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
    
    renderPagination(filteredTasks.length, currentPage, 'tasksPagination', (page) => {
        currentPage = page;
        renderTasksTable();
    });
}

// Render my tasks table
function renderMyTasksTable() {
    const tbody = document.getElementById('myTasksTableBody');
    
    if (myFilteredTasks.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-5">
                    <h6 class="text-muted">Không có công việc nào</h6>
                </td>
            </tr>
        `;
        document.getElementById('myTasksPagination').innerHTML = '';
        return;
    }
    
    // Pagination
    const startIndex = (myCurrentPage - 1) * tasksPerPage;
    const endIndex = startIndex + tasksPerPage;
    const paginatedTasks = myFilteredTasks.slice(startIndex, endIndex);
    
    tbody.innerHTML = paginatedTasks.map(task => `
        <tr>
            <td>
                <div class="fw-medium">${escapeHtml(task.content)}</div>
                ${task.description ? `<div class="text-muted small mt-1">${escapeHtml(task.description)}</div>` : ''}
            </td>
            <td>${formatTime(task.startTime, task.endTime)}</td>
            <td>
                <div>${formatDate(task.startDate)}</div>
                <div class="text-muted small">đến ${formatDate(task.endDate)}</div>
            </td>
            <td>${escapeHtml(task.createdBy || 'N/A')}</td>
            <td><span class="badge ${getStatusBadgeClass(task.status)}">${task.status}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-sm btn-outline-info" onclick="viewTask('${task.id}')" title="Xem">
                        <i class="bi bi-eye"></i>
                    </button>
                    ${task.status !== 'Đã hoàn thành' ? `
                        <button class="btn btn-sm btn-outline-success" onclick="openCompleteDialog('${task.id}')" title="Hoàn thành">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
    
    renderPagination(myFilteredTasks.length, myCurrentPage, 'myTasksPagination', (page) => {
        myCurrentPage = page;
        renderMyTasksTable();
    });
}

// Render pagination
function renderPagination(totalItems, currentPage, containerId, onPageChange) {
    const container = document.getElementById(containerId);
    const totalPages = Math.ceil(totalItems / tasksPerPage);
    
    if (totalPages <= 1) {
        container.innerHTML = '';
        return;
    }
    
    let html = '';
    
    // Previous button
    html += `
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); ${currentPage > 1 ? `changePage(${currentPage - 1}, '${containerId}')` : ''}">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); changePage(${i}, '${containerId}')">${i}</a>
                </li>
            `;
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    // Next button
    html += `
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); ${currentPage < totalPages ? `changePage(${currentPage + 1}, '${containerId}')` : ''}">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;
    
    container.innerHTML = html;
}

// Change page
function changePage(page, containerId) {
    if (containerId === 'tasksPagination') {
        currentPage = page;
        renderTasksTable();
    } else if (containerId === 'myTasksPagination') {
        myCurrentPage = page;
        renderMyTasksTable();
    }
}

// Open task dialog
function openTaskDialog(taskId = null) {
    const form = document.getElementById('taskForm');
    form.reset();
    
    if (taskId) {
        const task = tasks.find(t => t.id === taskId);
        if (task) {
            document.getElementById('taskModalTitle').textContent = 'Sửa công việc';
            document.getElementById('taskId').value = task.id;
            document.getElementById('taskContent').value = task.content;
            document.getElementById('taskDescription').value = task.description;
            document.getElementById('taskStartTime').value = task.startTime || '';
            document.getElementById('taskEndTime').value = task.endTime || '';
            document.getElementById('taskStartDate').value = task.startDate.split('T')[0];
            document.getElementById('taskEndDate').value = task.endDate.split('T')[0];
            document.getElementById('taskAssignee').value = task.assignee;
            document.getElementById('taskStatus').value = task.status;
            
            if (task.attachmentName) {
                document.getElementById('currentAttachment').textContent = `Tệp hiện tại: ${task.attachmentName}`;
            }
        }
    } else {
        document.getElementById('taskModalTitle').textContent = 'Thêm công việc mới';
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('taskStartDate').value = today;
        document.getElementById('taskEndDate').value = today;
        document.getElementById('currentAttachment').textContent = '';
    }
    
    taskModal.show();
}

// Edit task
function editTask(taskId) {
    openTaskDialog(taskId);
}

// Save task
function saveTask() {
    const form = document.getElementById('taskForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const taskId = document.getElementById('taskId').value;
    const attachmentInput = document.getElementById('taskAttachment');
    const attachmentName = attachmentInput.files.length > 0 ? attachmentInput.files[0].name : null;
    
    const taskData = {
        content: document.getElementById('taskContent').value,
        description: document.getElementById('taskDescription').value,
        startTime: document.getElementById('taskStartTime').value,
        endTime: document.getElementById('taskEndTime').value,
        startDate: new Date(document.getElementById('taskStartDate').value).toISOString(),
        endDate: new Date(document.getElementById('taskEndDate').value).toISOString(),
        assignee: document.getElementById('taskAssignee').value,
        status: document.getElementById('taskStatus').value,
        attachmentName: attachmentName
    };
    
    if (taskId) {
        // Update existing task
        const index = tasks.findIndex(t => t.id === taskId);
        if (index !== -1) {
            tasks[index] = {
                ...tasks[index],
                ...taskData
            };
        }
    } else {
        // Create new task
        const newTask = {
            id: `task-${Date.now()}`,
            ...taskData,
            createdBy: currentUser,
            completionNote: null
        };
        tasks.push(newTask);
    }
    
    taskModal.hide();
    applyFilters();
    applyMyFilters();
}

// Open complete dialog
function openCompleteDialog(taskId) {
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        document.getElementById('completeTaskId').value = taskId;
        document.getElementById('completeTaskContent').textContent = task.content;
        document.getElementById('completionNote').value = '';
        completeModal.show();
    }
}

// Confirm complete
function confirmComplete() {
    const taskId = document.getElementById('completeTaskId').value;
    const completionNote = document.getElementById('completionNote').value;
    
    const index = tasks.findIndex(t => t.id === taskId);
    if (index !== -1) {
        tasks[index].status = 'Đã hoàn thành';
        tasks[index].completionNote = completionNote;
    }
    
    completeModal.hide();
    
    // Also hide view modal if it's open
    const viewModalElement = document.getElementById('viewModal');
    if (viewModalElement.classList.contains('show')) {
        viewModal.hide();
    }
    
    applyFilters();
    applyMyFilters();
}

// View task
function viewTask(taskId) {
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        document.getElementById('viewTaskId').value = taskId;
        document.getElementById('viewContent').textContent = task.content;
        document.getElementById('viewDescription').textContent = task.description || 'Không có mô tả';
        document.getElementById('viewTime').textContent = formatTime(task.startTime, task.endTime);
        document.getElementById('viewDate').textContent = `${formatDate(task.startDate)} - ${formatDate(task.endDate)}`;
        document.getElementById('viewAssignee').textContent = task.assignee;
        document.getElementById('viewCreatedBy').textContent = task.createdBy || 'N/A';
        
        const statusBadge = document.getElementById('viewStatus');
        statusBadge.textContent = task.status;
        statusBadge.className = `badge ${getStatusBadgeClass(task.status)}`;
        
        // Attachment
        const attachmentDiv = document.getElementById('viewAttachmentDiv');
        if (task.attachmentName) {
            document.getElementById('viewAttachment').textContent = task.attachmentName;
            attachmentDiv.style.display = 'block';
        } else {
            attachmentDiv.style.display = 'none';
        }
        
        // Completion note
        const completionNoteDiv = document.getElementById('viewCompletionNoteDiv');
        if (task.completionNote) {
            document.getElementById('viewCompletionNote').textContent = task.completionNote;
            completionNoteDiv.style.display = 'block';
        } else {
            completionNoteDiv.style.display = 'none';
        }
        
        // Show/hide complete button
        const completeBtn = document.getElementById('viewCompleteBtn');
        if (task.status === 'Đã hoàn thành') {
            completeBtn.style.display = 'none';
        } else {
            completeBtn.style.display = 'inline-block';
        }
        
        viewModal.show();
    }
}

// Open complete dialog from view modal
function openCompleteFromView() {
    const taskId = document.getElementById('viewTaskId').value;
    viewModal.hide();
    openCompleteDialog(taskId);
}

// Open delete dialog
function openDeleteDialog(taskId) {
    document.getElementById('deleteTaskId').value = taskId;
    deleteModal.show();
}

// Confirm delete
function confirmDelete() {
    const taskId = document.getElementById('deleteTaskId').value;
    tasks = tasks.filter(t => t.id !== taskId);
    
    deleteModal.hide();
    applyFilters();
    applyMyFilters();
}

// Render calendar
function renderCalendar() {
    const calendar = document.getElementById('calendar');
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();
    
    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    // Month names in Vietnamese
    const monthNames = [
        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ];
    
    let html = `<h4 class="text-center mb-4">${monthNames[month]} ${year}</h4>`;
    html += '<div class="row">';
    
    // Day headers
    const dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
    dayNames.forEach(day => {
        html += `<div class="col text-center fw-bold mb-2">${day}</div>`;
    });
    html += '</div><div class="row">';
    
    // Empty cells before first day
    for (let i = 0; i < startingDayOfWeek; i++) {
        html += '<div class="col"></div>';
    }
    
    // Calendar days
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = new Date(year, month, day);
        const dayTasks = tasks.filter(task => {
            const taskStart = new Date(task.startDate);
            const taskEnd = new Date(task.endDate);
            return currentDate >= new Date(taskStart.setHours(0,0,0,0)) && 
                   currentDate <= new Date(taskEnd.setHours(23,59,59,999));
        });
        
        html += '<div class="col">';
        html += '<div class="calendar-day">';
        html += `<div class="calendar-day-header">${day}</div>`;
        
        if (dayTasks.length > 0) {
            dayTasks.slice(0, 3).forEach(task => {
                html += `<div class="calendar-task" onclick="viewTask('${task.id}')" title="${escapeHtml(task.content)}">`;
                html += `<div class="text-truncate">${escapeHtml(task.content)}</div>`;
                html += '</div>';
            });
            
            if (dayTasks.length > 3) {
                html += `<div class="text-muted small">+${dayTasks.length - 3} công việc khác</div>`;
            }
        }
        
        html += '</div></div>';
        
        // New row after Sunday
        if ((startingDayOfWeek + day) % 7 === 0) {
            html += '</div><div class="row">';
        }
    }
    
    html += '</div>';
    calendar.innerHTML = html;
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function formatTime(startTime, endTime) {
    if (startTime && endTime) {
        return `${startTime} - ${endTime}`;
    } else if (startTime) {
        return `Từ ${startTime}`;
    } else if (endTime) {
        return `Đến ${endTime}`;
    } else {
        return 'Chưa xác định';
    }
}

function getStatusBadgeClass(status) {
    switch (status) {
        case 'Đã hoàn thành':
            return 'badge-success bg-success';
        case 'Hết hạn xử lý':
            return 'badge-danger bg-danger';
        default:
            return 'badge-warning bg-warning text-dark';
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

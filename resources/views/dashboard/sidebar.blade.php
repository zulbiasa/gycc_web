<style>
        .content {
    margin-left: 250px; /* Adjust content to the right of the sidebar */
    padding: 20px;
    height: 100vh; /* Ensure content stretches */
    overflow-y: auto; /* Scrollable content */
    transition: margin-left 0.3s; /* Add transition for a smooth effect */
}

.account-logout-container {
    position: absolute;
    bottom: 3vh; 
    left: 0;
    width: 100%;
    display: contents;
}

.sidebar {
    height: 100%;
    width: 250px; /* Adjust the width as needed */
    position: fixed;
    top: 0;
    left: 0;
    background: linear-gradient(135deg, #0056b3, #007bff); /* Example color, adjust as desired */
    overflow-x: hidden;
    padding-top: 20px;
    transition: 0.3s; /* Add transition for smooth opening/closing */
    z-index: 1; /* Ensure sidebar is above other content */
}

.close-btn {
    display: none; /* Hide by default on larger screens */
    text-align: right;
    padding: 10px;
    cursor: pointer;
    color: white; /* Adjust color as needed */
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px; /* Adjust size as needed */
}

/* Media Query for Mobile Devices */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%); /* Initially hide sidebar */
        width: 80%; /* Adjust the mobile sidebar width as needed */
    }

    .sidebar.open {
        transform: translateX(0); /* Show sidebar when open */
    }

    .content {
        margin-left: 0; /* Reset margin-left when sidebar is closed */
    }

    .content.open {
        margin-left: 80%; /* Adjust content when sidebar is open */
    }

    .close-btn {
        display: block; /* Show close button on mobile */
    }

    @media (max-height: 600px) {
        .account-logout-container {
            bottom: 2vh; /* Adjust for shorter viewports */
        }
    }
}
    </style>
<div class="sidebar">
    <div class="close-btn" onclick="closeSidebar()">
        <i class="fas fa-times"></i>
    </div>

    <!-- Logo Can Click -->
    <a href="/aboutus" style="text-decoration: none;">
        <div class="sidebar-logo">GYCC+</div>
    </a>

    <!-- Dashboard Link (No Child Links) -->
    <a href="{{ route('dashboard') }}" class="nav-link"><i class="fa fa-tachometer-alt"></i> Dashboard</a>

    <!-- Admin Sidebar -->
    @if ($role === 'Admin')
        <!-- User Management -->
        <a href="{{ route('users.view') }}" class="nav-link">
            <i class="fa fa-users"></i> User Management
        </a>
<!-- 
        <div id="user-management-links" class="child-links">
            <a href="{{ route('users.view') }}">View</a>
            <a href="{{ route('users.create') }}">Register</a>
            <a href="#">Update</a>
           
        </div> -->

        <!-- Service Management -->
        <a href="{{ route('services.view') }}" class="nav-link">
            <i class="fa fa-cogs"></i> Service Management
        </a>
        <!-- <div id="service-management-links" class="child-links">
            <a href="{{ route('services.view') }}">View</a>
            <a href="{{ route('services.create') }}">Add New</a>
        </div> -->

        <!-- Care Plan -->
        <a href="{{ route('careplan.index') }}" class="nav-link">
            <i class="fa fa-heartbeat"></i> Care Plan
        </a>

        <!-- Quotation -->
        <a href="{{ route('quotations.index') }}" class="nav-link">
            <i class="fa fa-file-invoice"></i> Quotation Management
        </a>
    @endif

    <!-- Caregiver Sidebar -->
    @if ($role === 'Caregiver')
        <!-- Client Management -->
        <a href="{{ route('viewClient') }}" class="nav-link">
            <i class="fa fa-user"></i> Client Management
        </a>
        <!-- <div id="client-management-links" class="child-links">
            <a href="{{ route('viewClient') }}">View</a>
        </div> -->

    

        <!-- Care Plan -->
        <a href="{{ route('viewCarePlan') }}" class="nav-link">
            <i class="fa fa-heart"></i> Care Plan
        </a>
        <!-- <div id="care-plan-caregiver-links" class="child-links">
            <a href="{{ route('registerCarePlan') }}">Register</a>
            <a href="{{ route('viewCarePlan') }}">View</a>
            <a href="#">Update</a>
        </div> -->

        <!-- Task Management -->
        <a href="{{ route('careLog') }}" class="nav-link">
            <i class="fa fa-tasks"></i> Task Management
        </a>
    @endif  

        <!-- Account and Logout Section -->
        <div class="account-logout-container">
            
            <a href="{{ route('myaccount.view') }}" class="nav-link account-link">
                <i class="fa fa-user-circle"></i> Account
            </a>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link text-danger logout-link" style="background: none; border: none; color: white; cursor: pointer; text-align:left;">
                    <i class="fa fa-sign-out-alt" style="color: red;"></i> Log Out
                </button>
            </form>
        </div>
</div>

<script>
//    function toggleMenu(menuId) {
//     const menu = document.getElementById(menuId);
//     const arrow = document.getElementById(menuId + '-arrow');

//     if (menu.style.display === 'block') {
//         menu.style.display = 'none'; 
//     } else {
//         menu.style.display = 'block'; 
//     }
// }

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    sidebar.classList.toggle('open');
    content.classList.toggle('open');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    sidebar.classList.remove('open');
    content.classList.remove('open');
}

</script>

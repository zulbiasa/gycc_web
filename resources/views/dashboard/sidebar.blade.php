<style>
        .content {
                    margin-left: 250px; /* Adjust content to the right of the sidebar */
                    padding: 20px;
                    height: 100vh; /* Ensure content stretches */
                    overflow-y: auto; /* Scrollable content */
        }
        .account-logout-container {
            position: absolute;
            bottom: 3vh; /* Adjust this value to control how far up it is from the bottom */
            left: 0;
            width: 100%;
            display:contents 
        }

        .close-btn {
            display: none;
            text-align: right;
            padding: 10px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .close-btn {
                display: block;
                
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
                background: linear-gradient(135deg, #0056b3, #007bff);
            }

            .sidebar.open {
                transform: translateX(0);
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
        <a href="#" class="nav-link" onclick="toggleMenu('user-management-links')">
            <i class="fa fa-users"></i> User Management
        </a>

        <div id="user-management-links" class="child-links">
            <a href="{{ route('users.view') }}">View</a>
            <a href="{{ route('users.create') }}">Register</a>
            <!-- <a href="#">Update</a> -->
           
        </div>

        <!-- Service Management -->
        <a href="#" class="nav-link" onclick="toggleMenu('service-management-links')">
            <i class="fa fa-cogs"></i> Service Management
        </a>
        <div id="service-management-links" class="child-links">
            <a href="{{ route('services.view') }}">View</a>
            <a href="{{ route('services.create') }}">Add New</a>
            <a href="#">Trend Services</a>
        </div>

        <!-- Care Plan -->
        <a href="{{ route('careplan.index') }}" class="nav-link" onclick="toggleMenu('care-plan-links')">
            <i class="fa fa-heartbeat"></i> Care Plan
        </a>
        <!-- <div id="care-plan-links" class="child-links">
            <a href="careplan">View</a>
            <a href="#">Update</a>
        </div> -->
    @endif

    <!-- Caregiver Sidebar -->
    @if ($role === 'Caregiver')
        <!-- Client Management -->
        <a href="#" class="nav-link" onclick="toggleMenu('client-management-links')">
            <i class="fa fa-user"></i> Client Management
        </a>
        <div id="client-management-links" class="child-links">
            <a href="{{ route('register') }}">Register</a>
            <a href="{{ route('viewClient') }}">View</a>
        </div>

    

        <!-- Care Plan -->
        <a href="#" class="nav-link" onclick="toggleMenu('care-plan-caregiver-links')">
            <i class="fa fa-heart"></i> Care Plan
        </a>
        <div id="care-plan-caregiver-links" class="child-links">
            <a href="{{ route('registerCarePlan') }}">Register</a>
            <a href="{{ route('viewCarePlan') }}">View</a>
            <!--<a href="#">Update</a>-->
        </div>

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
                <button type="submit" class="nav-link text-danger logout-link" style="background: none; border: none; color: white; cursor: pointer;">
                    <i class="fa fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </div>
</div>

<script>
   function toggleMenu(menuId) {
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(menuId + '-arrow');

    // Check if the menu is already visible
    if (menu.style.display === 'block') {
        menu.style.display = 'none'; // Hide the menu
      
    } else {
        menu.style.display = 'block'; // Show the menu
       
    }
}

    function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('visible');
}

</script>

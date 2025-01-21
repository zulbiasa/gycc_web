<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GYCC+')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            height: 100vh;
            /* background-color: #2f3b52; */
            background: linear-gradient(135deg, #0056b3, #007bff);
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
        }

         /* General styles */
         body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure body stretches full height */
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1; /* Push footer to the bottom */
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;

        }

        .sidebar a:hover {
            background-color:rgb(0, 6, 61);
        }

        /* Sidebar Hidden State for Mobile */
        .sidebar.hidden {
            transform: translateX(-100%);
        }

        /* Hamburger Icon */
        .hamburger {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 1100;
        }

        /* Close Icon */
        .close-btn {
            display: none;
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
        }

        /* .footer{
            z-index: 1001;
            background-color: #2f3b52;
        } */

        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, #0056b3, #007bff);
            color: white;
            z-index: 1001;
            padding: 20px;
            text-align: center;
            width: 100%;
          
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }

            .sidebar.visible {
                transform: translateX(0);
            }

            .close-btn {
                display: block;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }
        }

        @media (min-width: 769px) {
            .content {
                margin-left: 250px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Hamburger Menu -->
    <div class="hamburger" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    
    <!-- Sidebar -->
    @include('dashboard.sidebar')

    
    <!-- Main Content -->
    <div class="content">
        @include('dashboard.navbar')
        @yield('content')
        
    </div>

    @include('dashboard.footer')

    <script>
        function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const hamburger = document.querySelector('.hamburger');
        
        sidebar.classList.toggle('visible');

        // Hide the hamburger when the sidebar is visible
        if (sidebar.classList.contains('visible')) {
            hamburger.style.display = 'none';
        } else {
            hamburger.style.display = 'block';
        }
    }

        function closeSidebar() {
            const hamburger = document.querySelector('.hamburger');
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.remove('visible');
            hamburger.style.display = 'block';
        }
    </script>
</body>
</html>

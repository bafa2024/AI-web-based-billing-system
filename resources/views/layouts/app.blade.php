<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Invoice Management System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ecf0f1;
            font-size: 1.5rem;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 5px 0;
        }

        .sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
            color: #ecf0f1;
            border-left: 3px solid #3498db;
        }

        .sidebar ul li a.active {
            background-color: #3498db;
            color: white;
            border-left: 3px solid #2980b9;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Topbar Styles */
        .topbar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .topbar h1 {
            color: #2c3e50;
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            color: #555;
            font-weight: 500;
        }

        .profile-placeholder {
            width: 40px;
            height: 40px;
            background-color: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            
            .main-content {
                margin-left: 200px;
            }
            
            .topbar {
                padding: 10px 20px;
            }
            
            .content {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Invoice System</h2>
            <ul>
                <li><a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="/clients" class="{{ request()->is('clients*') ? 'active' : '' }}">Clients</a></li>
                <li><a href="/products" class="{{ request()->is('products*') ? 'active' : '' }}">Products</a></li>
                <li><a href="/invoices" class="{{ request()->is('invoices*') ? 'active' : '' }}">Invoices</a></li>
                <li><a href="/expenses" class="{{ request()->is('expenses*') ? 'active' : '' }}">Expenses</a></li>
                <li><a href="/reports" class="{{ request()->is('reports*') ? 'active' : '' }}">Reports</a></li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="user-info">
                    <span>Welcome, User</span>
                    <div class="profile-placeholder">U</div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StadiumPass | Premium Ticketing</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-main">
    <header class="glass">
        <div class="container">
            <nav>
                <a href="/" class="logo">Stadium<span style="color: white;">Pass</span></a>
                
                <ul class="nav-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/events">Matches</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role_name'] == 'customer'): ?>
                            <li><a href="/dashboard">My Tickets</a></li>
                        <?php elseif (in_array($_SESSION['role_name'], ['admin', 'super_admin'])): ?>
                            <li><a href="/admin/dashboard" style="color: var(--primary); font-weight: 600;">Admin Console</a></li>
                        <?php elseif ($_SESSION['role_name'] == 'staff'): ?>
                            <li><a href="/staff/scanner" style="color: var(--accent); font-weight: 600;">Gate Scanner</a></li>
                        <?php endif; ?>
                        <li><a href="/logout" class="btn btn-glass" style="margin-left: 1rem;">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login">Login</a></li>
                        <li><a href="/register" class="btn btn-primary">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container" style="padding-top: 2rem;">

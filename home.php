<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facebook_clone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f2f5;
        }

        /* Header Styles */
        .header {
            background-color: white;
            padding: 8px 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .logo {
            color: #1877f2;
            font-size: 2.5rem;
            font-weight: bold;
            text-decoration: none;
            margin-right: 10px;
        }

        .search-bar {
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 8px 16px;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            border: none;
            background: none;
            outline: none;
            margin-left: 8px;
            font-size: 0.9rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .icon-button {
            background-color: #f0f2f5;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Main Content Layout */
        .container {
            display: flex;
            margin-top: 60px;
            padding: 20px;
            gap: 20px;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 300px;
            position: fixed;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 8px;
            margin: 4px 0;
            border-radius: 8px;
            cursor: pointer;
        }

        .sidebar-item:hover {
            background-color: #e4e6e9;
        }

        .sidebar-item i {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 320px;
            flex-grow: 1;
            max-width: 680px;
        }

        .create-post {
            background-color: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .post-input {
            display: flex;
            align-items: center;
            padding: 8px;
            border-bottom: 1px solid #e4e6e9;
        }

        .post-input img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .post-input input {
            flex-grow: 1;
            border: none;
            background: #f0f2f5;
            padding: 8px 12px;
            border-radius: 20px;
            cursor: pointer;
        }

        .post-actions {
            display: flex;
            justify-content: space-around;
            padding-top: 8px;
        }

        .post-action {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
        }

        .post-action:hover {
            background-color: #f0f2f5;
        }

        /* Feed Posts */
        .post {
            background-color: white;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            padding: 12px;
            display: flex;
            align-items: center;
        }

        .post-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .post-content {
            padding: 12px;
        }

        .post-footer {
            padding: 12px;
            border-top: 1px solid #e4e6e9;
        }

        /* Right Sidebar */
        .right-sidebar {
            width: 300px;
            position: fixed;
            right: 0;
            height: calc(100vh - 60px);
            padding: 20px;
            overflow-y: auto;
        }

        .logout-btn {
            background-color: #1877f2;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #166fe5;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <a href="#" class="logo">f</a>
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search Facebook">
            </div>
        </div>
        <div class="header-right">
            <div class="icon-button">
                <i class="fas fa-user"></i>
            </div>
            <div class="icon-button">
                <i class="fas fa-bell"></i>
            </div>
            <div class="icon-button">
                <i class="fas fa-message"></i>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <div class="sidebar-item">
                <i class="fas fa-user"></i>
                <span><?php echo htmlspecialchars($email); ?></span>
            </div>
            <div class="sidebar-item">
                <i class="fas fa-user-friends"></i>
                <span>Friends</span>
            </div>
            <div class="sidebar-item">
                <i class="fas fa-users"></i>
                <span>Groups</span>
            </div>
            <div class="sidebar-item">
                <i class="fas fa-store"></i>
                <span>Marketplace</span>
            </div>
            <div class="sidebar-item">
                <i class="fas fa-tv"></i>
                <span>Watch</span>
            </div>
            <div class="sidebar-item">
                <i class="fas fa-history"></i>
                <span>Memories</span>
            </div>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Create Post -->
            <div class="create-post">
                <div class="post-input">
                    <img src="https://t3.ftcdn.net/jpg/05/66/26/98/360_F_566269813_8VisUzV5qqdN7nQ7De4FcVEVxnRuKh2E.jpg" alt="Profile Picture">
                    <input type="text" placeholder="What's on your mind?">
                </div>
                <div class="post-actions">
                    <div class="post-action">
                        <i class="fas fa-video" style="color: #f02849;"></i>
                        <span>Live Video</span>
                    </div>
                    <div class="post-action">
                        <i class="fas fa-images" style="color: #45bd62;"></i>
                        <span>Photo/Video</span>
                    </div>
                    <div class="post-action">
                        <i class="fas fa-smile" style="color: #f7b928;"></i>
                        <span>Feeling/Activity</span>
                    </div>
                </div>
            </div>

            <!-- Sample Posts -->
            <div class="post">
                <div class="post-header">
                    <img src="https://t3.ftcdn.net/jpg/05/66/26/98/360_F_566269813_8VisUzV5qqdN7nQ7De4FcVEVxnRuKh2E.jpg" alt="User">
                    <div>
                        <h3>JP master</h3>
                        <span>2 hours ago</span>
                    </div>
                </div>
                <div class="post-content">
                    <p>Ako nga pala si JP , Welcome sa aking Fakebook</p>
                </div>
                <div class="post-footer">
                    <div class="post-actions">
                        <div class="post-action">
                            <i class="fas fa-thumbs-up"></i>
                            <span>Like</span>
                        </div>
                        <div class="post-action">
                            <i class="fas fa-comment"></i>
                            <span>Comment</span>
                        </div>
                        <div class="post-action">
                            <i class="fas fa-share"></i>
                            <span>Share</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Right Sidebar -->
        <div class="right-sidebar">
            <h3>Contacts</h3>
            <!-- Add contact list here -->
        </div>
    </div>
</body>
</html>
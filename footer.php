<!-- footer.php -->
<footer>
    <div class="footer-content">
        <?php
        
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'admin') {
                echo '<a href="admin_dashboard.php">Return to Admin Dashboard</a>';
            } else if ($_SESSION['role'] == 'user') {
                echo '<a href="dashboard.php">Return to User Dashboard</a>';
            } else {
                echo '<a href="login.php">Return to Login</a>'; // Fallback for users without a role
            }
        } else {
            echo '<a href="login.php">Return to Login</a>'; // Fallback if not logged in
        }
        ?>
    </div>
</footer>

<style>
    main {
    padding-bottom: 50px; /* Adjust to match footer height */
}
    footer {
        background-color: #f8f9fa;
        padding: 10px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
        border-top: 1px solid #e1e1e1;
        text-align: center;
        z-index: 1000; /* Ensures the footer stays above other content */
    }
    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .footer-content a {
        color: #007bff;
        text-decoration: none;
        font-size: 16px;
    }
    .footer-content a:hover {
        text-decoration: underline;
    }
</style>

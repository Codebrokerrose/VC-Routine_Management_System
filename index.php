<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VC Routine Management System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="main-container">

        <!-- Header -->
        <header class="top-header">
            <div class="container-fluid d-flex justify-content-between align-items-center bg-transparent h-100">
                <div class="college-details">
                    <img src="assets/images/logo.jfif" class="logo" alt="College Logo">
                    <div>
                        <h1 class="fs-4 m-0 fw-bold">VIVEKANANDA COLLEGE</h1>
                        <h5 class="fs-6 m-0 opacity-75">(GOVT. SPONSORED)</h5>
                        <p class="small m-0 opacity-50 d-none d-sm-block">
                            Affiliated to University of Calcutta | NAAC Accredited
                        </p>
                    </div>
                </div>
                <div class="system-title d-none d-lg-block">
                    <i class="bi bi-calendar2-week"></i>
                    Routine Management System
                </div>
            </div>
        </header>

        <!-- Main Section -->
        <section class="login-section container-fluid p-0">
            <div class="row g-0 w-100 h-100 m-0">
                <!-- Left Image -->
                <div class="col-lg-7 d-none d-lg-block p-0">
                    <div class="left-image">
                        <img src="assets/images/college4.png" alt="College Image">
                        <div class="image-overlay"></div>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="col-lg-5 p-0">
                    <div class="login-wrapper">
                        <div class="login-card">
                            <div class="text-center mb-4">
                                <i class="bi bi-person-circle profile-icon"></i>
                                <h2>Welcome Back!</h2>
                                <p class="text-muted">Please login to continue</p>
                            </div>

                            <?php
                            if (isset($_GET['error'])) {
                                echo '<div class="alert alert-danger py-2">Invalid Email or Password</div>';
                            }
                            ?>

                            <form action="auth/authenticate.php" method="POST">
                                <!-- Email -->
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Email Address" required>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Password" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword()">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="d-flex justify-content-between mb-4 small">
                                    <div>
                                        <input type="checkbox" class="form-check-input me-1" id="rememberMe">
                                        <label for="rememberMe" class="user-select-none">Remember Me</label>
                                    </div>
                                    <a href="auth/forgot-password.php" class="forgot">Forgot Password?</a>
                                </div>

                                <!-- Login Button -->
                                <button class="btn login-btn w-100 py-2 fw-semibold" type="submit">
                                    <i class="bi bi-shield-lock-fill me-2"></i>LOGIN
                                </button>
                            </form>

                            <hr class="my-4 text-muted">

                            <div class="info-box">
                                <i class="bi bi-info-circle-fill text-secondary"></i>
                                <span>For any login related issues, please contact the College Administration.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer>
            ©
            <?php echo date("Y"); ?> Vivekananda College. All Rights Reserved.
        </footer>

    </div>

    <script>
        function togglePassword() {
            let password = document.getElementById("password");
            let icon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text";
                icon.className = "bi bi-eye-slash";
            } else {
                password.type = "password";
                icon.className = "bi bi-eye";
            }
        }
    </script>
</body>

</html>
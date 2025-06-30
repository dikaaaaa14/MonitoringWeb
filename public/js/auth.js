// Modify initializeApp function
function initializeApp() {
    // Load user data from storage
    userSystem.loadFromLocalStorage();
    const isLoggedIn = userSystem.loadSession();
    
    // Check login status and show appropriate screen
    if (isLoggedIn) {
        showDashboard();
    } else {
        showLoginPage();
    }
    
    // Initialize charts
    initializeCharts();
    
    // Set up event listeners
    setupEventListeners();
    setupAuthEventListeners(); // Add this line
    
    // Update connection status UI
    updateConnectionStatus();
}

// Add these new functions
function showLoginPage() {
    document.getElementById('login-page').style.display = 'flex';
    document.getElementById('dashboard').style.display = 'none';
}

function showDashboard() {
    document.getElementById('login-page').style.display = 'none';
    document.getElementById('dashboard').style.display = 'flex';
    updateUserInterface();
}

function setupAuthEventListeners() {
    // Switch between login and register forms
    document.getElementById('show-register-form').addEventListener('click', function() {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
    });
    
    document.getElementById('show-login-form').addEventListener('click', function() {
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    });
    
    // Handle login form submission
    document.getElementById('login-submit').addEventListener('click', function() {
        handleDirectLogin();
    });
    
    // Handle registration form submission
    document.getElementById('register-submit').addEventListener('click', function() {
        handleDirectRegister();
    });
    
    // Add enter key support for login/register forms
    document.getElementById('login-password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleDirectLogin();
        }
    });
    
    document.getElementById('confirm-password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleDirectRegister();
        }
    });
}

function handleDirectLogin() {
    // Clear previous errors
    clearLoginErrors();
    
    const username = document.getElementById('login-username').value.trim();
    const password = document.getElementById('login-password').value;
    
    // Validate inputs
    let hasErrors = false;
    
    if (!username) {
        document.getElementById('login-username-error').textContent = 'Username is required';
        hasErrors = true;
    }
    
    if (!password) {
        document.getElementById('login-password-error').textContent = 'Password is required';
        hasErrors = true;
    }
    
    if (hasErrors) return;
    
    // Try to login
    const result = userSystem.login(username, password);
    
    if (result.success) {
        showDashboard();
    } else {
        document.getElementById('login-form-error').textContent = result.message;
    }
}

function handleDirectRegister() {
    // Clear previous errors
    clearRegisterErrors();
    
    const username = document.getElementById('register-username').value.trim();
    const password = document.getElementById('register-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    
    // Validate inputs
    let hasErrors = false;
    
    if (!username) {
        document.getElementById('reg-username-error').textContent = 'Username is required';
        hasErrors = true;
    } else if (username.length < 3) {
        document.getElementById('reg-username-error').textContent = 'Username must be at least 3 characters';
        hasErrors = true;
    }
    
    if (!password) {
        document.getElementById('reg-password-error').textContent = 'Password is required';
        hasErrors = true;
    } else if (password.length < 6) {
        document.getElementById('reg-password-error').textContent = 'Password must be at least 6 characters';
        hasErrors = true;
    }
    
    if (password !== confirmPassword) {
        document.getElementById('confirm-password-error').textContent = 'Passwords do not match';
        hasErrors = true;
    }
    
    if (hasErrors) return;
    
    // Try to register
    const result = userSystem.register(username, password);
    
    if (result.success) {
        // Auto-login after registration
        userSystem.login(username, password);
        showDashboard();
    } else {
        document.getElementById('register-form-error').textContent = result.message;
    }
}

function clearLoginErrors() {
    document.getElementById('login-username-error').textContent = '';
    document.getElementById('login-password-error').textContent = '';
    document.getElementById('login-form-error').textContent = '';
}

function clearRegisterErrors() {
    document.getElementById('reg-username-error').textContent = '';
    document.getElementById('reg-password-error').textContent = '';
    document.getElementById('confirm-password-error').textContent = '';
    document.getElementById('register-form-error').textContent = '';
}

// Modify handleLogout function
function handleLogout() {
    userSystem.logout();
    showLoginPage();
}

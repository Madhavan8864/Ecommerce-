<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - eCart Electronics</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            width: 450px;
            max-width: 90%;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 40px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .password-strength {
            margin-top: 5px;
            height: 5px;
            border-radius: 3px;
            background: #e0e0e0;
            overflow: hidden;
        }
        .strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s;
        }
        .strength-text {
            font-size: 12px;
            margin-top: 5px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="header">
            <h1>Reset Password</h1>
            <p>Enter your new password</p>
        </div>
        
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            
            <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                @csrf
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>
                    @error('password')
                        <span style="color: #e74c3c; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <div id="matchError" style="color: #e74c3c; font-size: 12px; margin-bottom: 10px; display: none;">
                    Passwords do not match!
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">Reset Password</button>
            </form>
            
            <div class="back-link" style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login') }}" style="color: #667eea; text-decoration: none; font-size: 14px;">← Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            
            if (val.length >= 8) strength++;
            if (val.match(/[a-z]+/)) strength++;
            if (val.match(/[A-Z]+/)) strength++;
            if (val.match(/[0-9]+/)) strength++;
            if (val.match(/[$@#&!]+/)) strength++;
            
            const width = (strength / 5) * 100;
            strengthBar.style.width = width + '%';
            
            if (strength <= 2) {
                strengthBar.style.background = '#e74c3c';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#e74c3c';
            } else if (strength <= 4) {
                strengthBar.style.background = '#f39c12';
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#f39c12';
            } else {
                strengthBar.style.background = '#2ecc71';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#2ecc71';
            }
        });
        
        // Password match checker
        const confirmPassword = document.getElementById('password_confirmation');
        const matchError = document.getElementById('matchError');
        const submitBtn = document.getElementById('submitBtn');
        
        function checkMatch() {
            if (password.value && confirmPassword.value) {
                if (password.value !== confirmPassword.value) {
                    matchError.style.display = 'block';
                    submitBtn.disabled = true;
                } else {
                    matchError.style.display = 'none';
                    submitBtn.disabled = false;
                }
            }
        }
        
        password.addEventListener('input', checkMatch);
        confirmPassword.addEventListener('input', checkMatch);
    </script>
</body>
</html>
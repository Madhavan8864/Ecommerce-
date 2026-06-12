<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - eCart Electronics</title>
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
        .otp-container {
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
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .otp-input:focus {
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
            margin-top: 20px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }
        .btn-resend {
            width: 100%;
            padding: 12px;
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-resend:hover {
            background: #f0f3ff;
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
        .timer {
            text-align: center;
            margin: 15px 0;
            font-size: 14px;
            color: #666;
        }
        .timer strong {
            color: #667eea;
            font-size: 18px;
        }
        .email-info {
            background: #f0f3ff;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="header">
            <h1>Verify OTP</h1>
            <p>Enter the 6-digit code sent to your email</p>
        </div>
        
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            
            <div class="email-info">
                <strong>Email:</strong> {{ session('reset_email') }}
            </div>
            
            <form method="POST" action="{{ route('password.verify.otp.post') }}" id="otpForm">
                @csrf
                
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric" autofocus>
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric">
                </div>
                <input type="hidden" name="otp" id="otp">
                
                @error('otp')
                    <span style="color: #e74c3c; font-size: 12px; margin-top: 5px; display: block; text-align: center;">{{ $message }}</span>
                @enderror
                
                <div class="timer" id="timer">
                    Time remaining: <strong id="minutes">10</strong>:<strong id="seconds">00</strong>
                </div>
                
                <button type="submit" class="btn-submit">Verify OTP</button>
            </form>
            
            <form method="POST" action="{{ route('password.otp.resend') }}" id="resendForm">
                @csrf
                <button type="submit" class="btn-resend" id="resendBtn">Resend OTP</button>
            </form>
        </div>
    </div>

    <script>
        // OTP Input Auto-focus
        const inputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otp');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateOtp();
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && index > 0 && this.value.length === 0) {
                    inputs[index - 1].focus();
                }
                updateOtp();
            });
            
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = e.clipboardData.getData('text');
                if (paste.length === 6 && /^\d+$/.test(paste)) {
                    paste.split('').forEach((char, i) => {
                        if (inputs[i]) inputs[i].value = char;
                    });
                    inputs[5].focus();
                    updateOtp();
                }
            });
        });
        
        function updateOtp() {
            let otp = '';
            inputs.forEach(input => otp += input.value);
            otpHidden.value = otp;
        }
        
        // Timer
        let timeLeft = 600; // 10 minutes in seconds
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        
        const timer = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            minutesEl.textContent = minutes.toString().padStart(2, '0');
            secondsEl.textContent = seconds.toString().padStart(2, '0');
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                document.querySelector('.btn-submit').disabled = true;
                document.querySelector('.timer').innerHTML = '<strong style="color: #e74c3c;">OTP Expired. Please resend.</strong>';
            }
        }, 1000);
        
        // Disable resend button for 30 seconds
        const resendBtn = document.getElementById('resendBtn');
        let resendTimer = 30;
        
        function updateResendBtn() {
            if (resendTimer > 0) {
                resendBtn.disabled = true;
                resendBtn.textContent = `Resend OTP (${resendTimer}s)`;
                resendTimer--;
                setTimeout(updateResendBtn, 1000);
            } else {
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend OTP';
            }
        }
        
        updateResendBtn();
    </script>
</body>
</html>
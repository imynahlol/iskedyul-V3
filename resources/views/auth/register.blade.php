<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | iSKEDYUL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(128, 0, 0, .12), transparent 40%),
                #fffdf7;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 35px 16px;
            color: #292124;
        }

        .container {
            width: 100%;
            max-width: 540px;
            padding: 38px;
            background: rgba(255, 255, 255, .82);
            border: 1px solid rgba(128, 0, 0, .10);
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(60, 20, 25, .09);
            backdrop-filter: blur(16px);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-mark {
            width: 54px;
            height: 54px;
            margin: 0 auto 14px;
            border-radius: 16px;
            background: #800000;
            color: #ffdf00;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 23px;
            font-weight: 800;
        }

        .brand h1 {
            font-size: 27px;
            color: #800000;
            font-weight: 800;
        }

        .brand p {
            color: #777;
            font-size: 13px;
            margin-top: 7px;
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .section-subtitle {
            color: #777;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 17px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #e3dfe0;
            border-radius: 10px;
            outline: none;
            background: #fff;
            font-family: inherit;
            font-size: 13px;
            transition: .2s ease;
        }

        input:focus, select:focus {
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, .08);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
        }

        .helper {
            color: #888;
            font-size: 11px;
            margin-top: 6px;
        }

        .error {
            color: #b42318;
            font-size: 12px;
            margin-top: 6px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            background: #800000;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .submit-btn:hover {
            background: #600000;
            transform: translateY(-1px);
        }

        .login-link {
            text-align: center;
            font-size: 13px;
            color: #777;
            margin-top: 22px;
        }

        .login-link a {
            color: #800000;
            font-weight: 700;
            text-decoration: none;
        }

        .notice {
            background: #fff9dc;
            border: 1px solid #f2df8c;
            padding: 12px;
            border-radius: 9px;
            font-size: 12px;
            color: #665000;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .container {
                padding: 26px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="brand">
        <div class="brand-mark">i</div>
        <h1>iSKEDYUL</h1>
        <p>Intelligent Scheduling and Classroom Reservation System</p>
    </div>

    <h2 class="section-title">Create your account</h2>
    <p class="section-subtitle">
        Register using your official PUP identification number.
    </p>

    <div class="notice">
        <strong>Registration reminder</strong><br>
        Students must use the format 20XX-XXXXX-LQ-X.
        Faculty members must use their five-digit faculty ID.
    </div>

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div class="form-group">
            <label for="user_type">Account Type</label>

            <select name="user_type" id="user_type" required>
                <option value="">Select account type</option>
                <option value="Student" {{ old('user_type') == 'Student' ? 'selected' : '' }}>Student</option>
                <option value="Faculty" {{ old('user_type') == 'Faculty' ? 'selected' : '' }}>Faculty</option>
            </select>

            @error('user_type')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="user_id" id="id-label">Student / Faculty ID</label>

            <input
                type="text"
                name="user_id"
                id="user_id"
                value="{{ old('user_id') }}"
                placeholder="Select your account type first"
                required
            >

            <p class="helper" id="id-helper">
                Your official university identification number.
            </p>

            @error('user_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">Full Name</label>

            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                placeholder="Enter your full name"
                required
            >

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div id="student-fields">
            <div class="form-group">
                <label for="program">Program / Course</label>

                <input
                    type="text"
                    name="program"
                    id="program"
                    value="{{ old('program') }}"
                    placeholder="e.g. BS Information Technology"
                >

                @error('program')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="year_level">Year Level</label>

                    <select name="year_level" id="year_level">
                        <option value="">Select year</option>
                        <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="section">Section</label>

                    <input
                        type="text"
                        name="section"
                        id="section"
                        value="{{ old('section') }}"
                        placeholder="e.g. LQ 2-1"
                    >
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address (Optional)</label>

            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                placeholder="example@email.com"
            >

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>

            <input
                type="password"
                name="password"
                id="password"
                placeholder="At least 8 characters"
                required
            >

            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>

            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                placeholder="Re-enter your password"
                required
            >
        </div>

        <button type="submit" class="submit-btn">
            Create Account
        </button>

    </form>

    <div class="login-link">
        Already have an account?
        <a href="{{ route('login') }}">Log in</a>
    </div>

</div>

<script>
    const userType = document.getElementById('user_type');
    const userId = document.getElementById('user_id');
    const idLabel = document.getElementById('id-label');
    const idHelper = document.getElementById('id-helper');
    const studentFields = document.getElementById('student-fields');

    function updateRegistrationForm() {
        if (userType.value === 'Student') {
            idLabel.textContent = 'Student ID';
            userId.placeholder = '2025-00122-LQ-0';
            idHelper.textContent = 'Format: 20XX-XXXXX-LQ-X';
            studentFields.style.display = 'block';
        } else if (userType.value === 'Faculty') {
            idLabel.textContent = 'Faculty ID';
            userId.placeholder = 'e.g. 12345';
            idHelper.textContent = 'Enter exactly 5 numeric digits.';
            studentFields.style.display = 'none';
        } else {
            idLabel.textContent = 'Student / Faculty ID';
            userId.placeholder = 'Select your account type first';
            idHelper.textContent = 'Your official university identification number.';
            studentFields.style.display = 'block';
        }
    }

    userType.addEventListener('change', updateRegistrationForm);

    updateRegistrationForm();
</script>

</body>
</html>
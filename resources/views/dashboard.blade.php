<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | iSKEDYUL</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f8f6f2;
            color: #302326;
        }

        .navbar {
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
            background: #800000;
            color: white;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand span {
            color: #ffdf00;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            font-size: 12px;
            font-weight: 500;
        }

        .logout-button {
            padding: 9px 16px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 8px;
            background: transparent;
            color: white;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .logout-button:hover {
            background: white;
            color: #800000;
        }

        .container {
            width: 88%;
            max-width: 1250px;
            margin: 45px auto;
        }

        .welcome-section {
            margin-bottom: 32px;
        }

        .welcome-section h1 {
            font-size: 27px;
            font-weight: 700;
            color: #800000;
        }

        .welcome-section p {
            margin-top: 7px;
            color: #887d7f;
            font-size: 13px;
        }

        .welcome-card {
            padding: 32px;
            border-radius: 18px;
            background: linear-gradient(120deg, #800000, #a52a2a);
            color: white;
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.13);
        }

        .welcome-card h2 {
            font-size: 21px;
            font-weight: 700;
        }

        .welcome-card p {
            margin-top: 10px;
            max-width: 650px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            line-height: 1.8;
        }

        .section-heading {
            margin: 35px 0 17px;
            font-size: 17px;
            font-weight: 700;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .dashboard-card {
            padding: 23px;
            border: 1px solid #eee7e5;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.025);
        }

        .card-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 17px;
            border-radius: 12px;
            background: #f8eeee;
            color: #800000;
            font-size: 18px;
            font-weight: 700;
        }

        .dashboard-card h3 {
            font-size: 14px;
            font-weight: 700;
        }

        .dashboard-card p {
            margin-top: 7px;
            color: #96898c;
            font-size: 11px;
            line-height: 1.7;
        }

        .notice {
            margin-top: 28px;
            padding: 18px 20px;
            border: 1px solid #f1e4b2;
            border-radius: 12px;
            background: #fffbea;
            color: #796321;
            font-size: 11px;
            line-height: 1.8;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0 5%;
            }

            .user-name {
                display: none;
            }

            .container {
                width: 90%;
                margin: 30px auto;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .welcome-section h1 {
                font-size: 22px;
            }

            .welcome-card {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">i<span>SKEDYUL</span></div>

        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name }}</span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="container">

        <section class="welcome-section">
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <p>Here's your iSKEDYUL student and faculty dashboard.</p>
        </section>

        <section class="welcome-card">
            <h2>Classroom scheduling made easier.</h2>

            <p>
                Welcome to iSKEDYUL, the Intelligent Scheduling and Classroom
                Reservation System of PUP Lopez Campus. Manage your classroom
                reservations and check room availability in one place.
            </p>
        </section>

        <h2 class="section-heading">Quick Overview</h2>

        <section class="dashboard-grid">

            <div class="dashboard-card">
                <div class="card-icon">01</div>
                <h3>Reserve a Classroom</h3>
                <p>Book an available classroom for your academic or organizational activities.</p>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">02</div>
                <h3>Room Availability</h3>
                <p>Check classroom schedules and identify available rooms before booking.</p>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">03</div>
                <h3>My Reservations</h3>
                <p>View and manage your upcoming and previous classroom reservations.</p>
            </div>

        </section>

        <div class="notice">
            <strong>System Notice:</strong>
            Reservation management and room availability features are currently
            being developed. Stay tuned for the next updates.
        </div>

    </main>

</body>
</html>
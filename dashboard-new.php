<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landowner Dashboard</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        section {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
            margin: 10px;
            border-radius: 5px;
        }

        h2 {
            color: #333;
        }

        .section-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-item {
            flex: 1;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            margin: 10px;
        }

        .pie-chart {
            flex: 1;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            margin: 10px;
            text-align: center;
        }

        #tenantPieChart,
        #reservationPieChart,
        #propertyOverviewPieChart {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        #feedbackSection {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 10px;
            flex: 1; /* Take full width */
            margin-top: 20px; /* Adjust the top margin as needed */
            display: flex;
            flex-direction: column;
            align-items: center;
            max-height: 300px; /* Adjust the max-height as needed */
            overflow-y: auto;
        }

        #feedbackList {
            list-style: none;
            padding: 0;
            width: 100%;
            max-width: 400px; /* Adjust the max-width as needed */
        }

        #feedbackList li {
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            text-align: center;
        }

        /* Adjusted pie chart sizes */
        canvas {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <section>
        <h2>Property Overview</h2>
        <div class="section-content">
            <div class="section-item">
                <h3>Total number of maps</h3>
                <p>10</p>
            </div>
            <div class="section-item">
                <h3>Total number of spaces</h3>
                <p>100</p>
            </div>
            <div class="pie-chart">
                <canvas id="propertyOverviewPieChart"></canvas>
            </div>
        </div>
    </section>

    <section>
        <h2>Tenant Management</h2>
        <div class="section-content">
            <div class="section-item">
                <h3>Total number of maps</h3>
                <p>5</p>
            </div>
            <div class="pie-chart">
                <canvas id="tenantPieChart"></canvas>
            </div>
        </div>
    </section>

    <section>
        <h2>Financial Overview</h2>
        <div class="section-content">
            <div class="section-item">
                <h3>Monthly revenue</h3>
                <p>$10,000</p>
            </div>
            <div class="section-item">
                <button>Billing Information</button>
            </div>
            <div class="section-item">
                <button>Financial Reports</button>
            </div>
        </div>
    </section>

    <section>
        <h2>Reservation and Application Tracking</h2>
        <div class="section-content">
            <div class="section-item">
                <h3>Pending reservations</h3>
                <p>3</p>
            </div>
            <div class="section-item">
                <h3>Pending applications</h3>
                <p>2</p>
            </div>
            <div class="pie-chart">
                <canvas id="reservationPieChart"></canvas>
            </div>
        </div>
    </section>

    <section id="feedbackSection">
        <h2>Feedback</h2>
        <ul id="feedbackList">
            <li>Feedback 1</li>
            <li>Feedback 2</li>
            <li>Feedback 1</li>
            <li>Feedback 2</li>
            <li>Feedback 1</li>
            <li>Feedback 2</li>
            <li>Feedback 1</li>
            <li>Feedback 2</li>
            <!-- Add more feedback items -->
        </ul>
    </section>

    <script>
        // Mock data for feedback
        const feedbackData = [
            { user: 'Tenant 1', feedback: 'Positive feedback.' },
            { user: 'Tenant 2', feedback: 'Negative feedback.' },
        ];

        // Dynamically populate feedback list
        const feedbackList = document.getElementById('feedbackList');
        feedbackData.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.feedback} from ${item.user}`;
            feedbackList.appendChild(li);
        });

        // Mock data for pie charts
        const tenantPieData = {
            labels: ['Pending Users', 'Active Users'],
            datasets: [{
                data: [30, 70],
                backgroundColor: ['#FF6384', '#36A2EB'],
            }],
        };

        const reservationPieData = {
            labels: ['Reservations', 'Applications'],
            datasets: [{
                data: [40, 60],
                backgroundColor: ['#FFCE56', '#4CAF50'],
            }],
        };

        const propertyOverviewPieData = {
            labels: ['Occupied', 'Vacant'],
            datasets: [{
                data: [80, 20],
                backgroundColor: ['#FFCE56', '#4CAF50'],
            }],
        };

        // Render pie charts
        const tenantPieChart = new Chart(document.getElementById('tenantPieChart'), {
            type: 'pie',
            data: tenantPieData,
        });

        const reservationPieChart = new Chart(document.getElementById('reservationPieChart'), {
            type: 'pie',
            data: reservationPieData,
        });

        const propertyOverviewPieChart = new Chart(document.getElementById('propertyOverviewPieChart'), {
            type: 'pie',
            data: propertyOverviewPieData,
        });
    </script>
</body>

</html>

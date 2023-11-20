<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Tenants Page</title>
    <style>
        /* Styles remain unchanged from the previous code */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        section {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        .leases-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .leases-table th, .leases-table td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .leases-table th {
            background-color: #666;
            color: white;
        }

        .hidden {
            display: none;
        }

        .tenant-row {
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 12%;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            
        }

        .close-modal {
            cursor: pointer;
            margin-top: 10px;
            color: #333;
        }
    </style>
</head>

<body>
    <header>
        <h1>Owner Tenants Page</h1>
    </header>

    <section>
        <h2>Tenant Information</h2>

        <table id="tenantTable">
            <thead>
                <tr>
                    <th>Tenant ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Lease Start Date</th>
                    <th>Lease End Date</th>
                </tr>
            </thead>
            <tbody>
                <tr data-tenantid="1" class="tenant-row">
                    <td>1</td>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>(123) 456-7890</td>
                    <td>2023-01-01</td>
                    <td>2023-12-31</td>
                </tr>
                <tr data-tenantid="2" class="tenant-row">
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>jane@example.com</td>
                    <td>(987) 654-3210</td>
                    <td>2022-06-15</td>
                    <td>2023-06-14</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>

        <div id="leaseModal" class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="closeLeaseModal()"></span>
                <h3>Leases</h3>
                <table id="leasesTable" class="leases-table">
                    <thead>
                        <tr>
                            <th>Space ID</th>
                            <th>Lease Start Date</th>
                            <th>Lease End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Lease data for Tenant 1 -->
                        <tr>
                            <td>101</td>
                            <td>2023-01-01</td>
                            <td>2023-06-30</td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>2023-02-01</td>
                            <td>2023-07-31</td>
                        </tr>
                        <!-- Lease data for Tenant 2 -->
                        <tr>
                            <td>201</td>
                            <td>2023-03-01</td>
                            <td>2023-08-31</td>
                        </tr>
                        <tr>
                            <td>202</td>
                            <td>2023-04-01</td>
                            <td>2023-09-30</td>
                        </tr>
                        <!-- Add more lease rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tenantTable = document.getElementById('tenantTable');
            const leaseModal = document.getElementById('leaseModal');
            const closeLeaseModalBtn = document.querySelector('.close-modal');

            tenantTable.addEventListener('click', function (event) {
                const target = event.target.closest('.tenant-row');
                if (target) {
                    // Show the modal
                    leaseModal.style.display = 'block';
                }
            });

            closeLeaseModalBtn.addEventListener('click', function () {
                // Close the modal
                leaseModal.style.display = 'none';
            });

            window.addEventListener('click', function (event) {
                // Close the modal if clicked outside the modal content
                if (event.target === leaseModal) {
                    leaseModal.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>

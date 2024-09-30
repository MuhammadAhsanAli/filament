<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Management Dashboard Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9; /* Added a light background for contrast */
        }

        .container {
            max-width: 800px;
            margin: 20px auto; /* Added margin for spacing */
            padding: 20px;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px; /* Added margin for spacing */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            border: 1px solid #dddddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            th, td {
                padding: 8px; /* Reduced padding for smaller screens */
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Seat Management Dashboard Report</h1>
    <table>
        <thead>
        <tr>
            <th>Seat No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Meals</th>
            <th>Drinks</th>
            <th>Comments</th>
        </tr>
        </thead>
        <tbody>
        <!-- Dynamic data population -->
        @foreach($records->seat as $data)
            <tr>
                <td>{{ (int) $data->id }}</td>
                <td>{{ $data->seatDetails->first()?->order?->name ?? 'N/A' }}</td>
                <td>{{ $data->seatDetails->first()?->order?->email ?? 'N/A' }}</td>
                <td>{{ $data->seatDetails->first()?->order->first()?->dishDetails->first()?->dish?->meal ?? 'N/A' }}</td>
                <td>{{ $data->seatDetails->first()?->order->first()?->drinkDetails->first()?->drink?->name ?? 'N/A' }}</td>
                <td>{{ $data->seatDetails->first()?->order?->comments ?? 'Unreserved' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

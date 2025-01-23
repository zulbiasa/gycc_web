<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .quotation-details {
            margin-top: 20px;
        }
        .quotation-details div {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Quotation Details</h1>
    <div class="quotation-details">
        <div><strong>Name:</strong> {{ $quotation['name'] }}</div>
        <div><strong>Email:</strong> {{ $quotation['email'] }}</div>
        <div><strong>Phone:</strong> {{ $quotation['phone'] }}</div>
        <div><strong>Services:</strong> {{ implode(', ', $quotation['services']) }}</div>
        <div><strong>Total Cost (RM):</strong> {{ number_format($quotation['totalCost'], 2) }}</div>
        <div><strong>Assigned Admin:</strong> {{ $adminName ?? 'Unassigned' }}</div>
        <div><strong>Status:</strong> {{ $quotation['status'] }}</div>
    </div>
</body>
</html>

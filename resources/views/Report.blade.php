<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Taxi Movement Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .tego-bg-red { background-color: #e74c3c !important; color: white; }
        .tego-bg-green { background-color: #27ae60 !important; color: white; }
        .tego-color-red { color: #c0392b; font-weight: bold; }
        .tego-color-green { color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h2>Daily Taxi Movement Report</h2>
        <div class="panel panel-primary">
            <div class="panel-heading">Summary for {{ \Carbon\Carbon::today()->toFormattedDateString() }}</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">Total Movements Today:</div>
                    <div class="col-md-6">{{ $numberOfMovementsToday }}</div>

                    <div class="col-md-6">Completed Movements Today:</div>
                    <div class="col-md-6">{{ $numberOfCompletedMovementsToday }}</div>

                    <div class="col-md-6">Rejected Movements Today:</div>
                    <div class="col-md-6">{{ $numberOfRejectedMovementsToday }}</div>

                    <div class="col-md-6">Canceled Movements Today:</div>
                    <div class="col-md-6">{{ $numberOfCanceledMovementsToday }}</div>

                    <div class="col-md-6">Total Amount Earned Today:</div>
                    <div class="col-md-6">{{ $totalAmount }}</div>
                </div>
            </div>
        </div>

        <!-- Movements Details -->
        <h3>Movements Today</h3>
        @foreach($movementsToday as $movement)
            <div class="panel panel-default">
                <div class="panel-heading">{{ $movement->movement_type->name }} - {{ $movement->driver->name }}</div>
                <div class="panel-body">
                    <p>Amount: {{ $movement->calculations->sum('totalPrice') }}</p>
                    <p>Status: {{ $movement->is_completed ? 'Completed' : 'Pending' }}</p>
                </div>
            </div>
        @endforeach

        <!-- Drivers Movements Summary -->
        <h3>Drivers Movements Summary</h3>
        @foreach($driversMovements as $driver)
            <div class="panel panel-info">
                <div class="panel-heading">{{ $driver->name }}</div>
                <div class="panel-body">
                    <p>Total Movements: {{ $driver->driver_movements->count() }}</p>
                    <p>Average Rating: {{ $driver->driver_ratings->avg('rating') }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>

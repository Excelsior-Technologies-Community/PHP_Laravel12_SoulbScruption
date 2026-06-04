<!DOCTYPE html>
<html>
<head>
    <title>Admin Analytics Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; padding: 40px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; }
        .card h3 { color: #6b7280; font-size: 14px; text-transform: uppercase; margin: 0; }
        .card p { font-size: 28px; font-weight: bold; color: #111827; margin: 10px 0 0; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        th { background: #111827; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #e5e7eb; }
        h2 { color: #111827; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>📊 Analytics Overview</h1>
    <div class="dashboard-grid">
        <div class="card"><h3>Total Revenue</h3><p>${{ number_format($revenue, 2) }}</p></div>
        <div class="card"><h3>Active Users</h3><p>{{ $activeUsers }}</p></div>
    </div>

    <h2>Active Subscriptions</h2>
    <table>
        <tr><th>Subscriber</th><th>Plan</th><th>Started At</th></tr>
        @foreach($subscriptions as $sub)
        <tr><td>{{ $sub->subscriber->name ?? 'N/A' }}</td><td>{{ $sub->plan->name }}</td><td>{{ $sub->started_at->format('d M, Y') }}</td></tr>
        @endforeach
    </table>

    <h2>📜 Recent Invoices</h2>
    <table>
        <tr><th>ID</th><th>Amount</th><th>Status</th></tr>
        @foreach($invoices as $inv)
        <tr><td>{{ $inv->id }}</td><td>${{ $inv->amount }}</td><td>{{ $inv->status }}</td></tr>
        @endforeach
    </table>

    <h2>🎟️ Coupons</h2>
    <table>
        <tr><th>Code</th><th>Discount</th></tr>
        @foreach($coupons as $c)
        <tr><td>{{ $c->code }}</td><td>{{ $c->discount_percent }}%</td></tr>
        @endforeach
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symptom Analysis History</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Vanilla CSS (no custom properties) */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .history-item {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        .history-item:hover {
            border-color: #2563eb;
        }

        .urgency-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 12px;
            margin-right: 10px;
        }

        .urgency-emergency { background: #fee2e2; color: #dc2626; }
        .urgency-high { background: #fef3c7; color: #f59e0b; }
        .urgency-medium { background: #dbeafe; color: #2563eb; }
        .urgency-low { background: #dcfce7; color: #10b981; }

        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-history"></i> Symptom Analysis History</h1>
        <p>Your recent symptom analyses and results</p>
    </div>

    <div class="card">
        @if(count($history) > 0)
            @foreach($history as $analysis)
                <div class="history-item">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                        <h3 style="flex: 1; margin: 0; color: #1e293b;">
                            {{ Str::limit($analysis['symptoms'], 100) }}
                        </h3>
                        <span class="urgency-badge urgency-{{ $analysis['urgency_level'] }}">
                                {{ strtoupper($analysis['urgency_level']) }}
                            </span>
                    </div>

                    <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 10px;">
                        <i class="fas fa-calendar"></i> {{ $analysis['timestamp'] }}
                        • <i class="fas fa-symptoms"></i> {{ $analysis['matched_symptoms_count'] }} symptoms matched
                        • <i class="fas fa-percentage"></i> {{ ($analysis['urgency_score'] * 100) }}% urgency score
                    </div>

                    <div style="font-size: 0.875rem; color: #64748b;">
                        <strong>Analysis ID:</strong> {{ $analysis['query_id'] }}
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>No Analysis History</h3>
                <p>You haven't analyzed any symptoms yet.</p>
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('symptom-checker') }}" class="btn">
                <i class="fas fa-arrow-left"></i> Back to Symptom Checker
            </a>
        </div>
    </div>
</div>
</body>
</html>

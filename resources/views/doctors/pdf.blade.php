<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Doctors List</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            line-height: 1.4;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even){
            background-color: #f2f2f2;
        }
        .description {
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Doctors List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Specialty</th>
                <th>Description</th>
                <th>Avg. Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->email }}</td>
                    <td>{{ $doctor->phone ?? 'N/A' }}</td>
                    <td>{{ $doctor->specialty->name ?? 'N/A' }}</td>
                    <td class="description">{{ $doctor->description ?? 'No description' }}</td>
                    <td>
                        @php
                            $avgRating = $doctor->reviews->avg('rating');
                        @endphp
                        {{ $avgRating ? number_format($avgRating, 1) . '/5' : 'No reviews' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Total doctors: {{ $doctors->count() }}</p>
</body>
</html>

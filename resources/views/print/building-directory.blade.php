c<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Directory - iSKEDYUL</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: #f3f4f6;
            margin: 0;
            padding: 25px;
        }

        .actions {
            max-width: 900px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .actions button {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .print-button {
            background: #7B1E2B;
            color: white;
        }

        .back-button {
            background: #e5e7eb;
            color: #333;
        }

        .paper {
            max-width: 900px;
            min-height: 800px;
            margin: auto;
            padding: 45px;
            background: white;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #7B1E2B;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #7B1E2B;
            font-size: 25px;
            margin: 0 0 6px;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
            color: #555;
        }

        .document-title {
            text-align: center;
            margin: 25px 0;
        }

        .document-title h2 {
            font-size: 19px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .document-title p {
            color: #666;
            font-size: 13px;
            margin: 4px;
        }

        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: #7B1E2B;
            color: white;
            text-align: left;
            padding: 11px 9px;
        }

        td {
            border: 1px solid #ddd;
            padding: 10px 9px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background: #f9f5f6;
        }

        .status {
            font-weight: bold;
        }

        .summary {
            margin-top: 18px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }

        .footer {
            margin-top: 45px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #777;
            font-size: 10px;
        }

        @page {
            size: A4 portrait;
            margin: 15mm;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .actions {
                display: none;
            }

            .paper {
                max-width: none;
                min-height: auto;
                padding: 0;
                box-shadow: none;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="actions">
        <button class="back-button" onclick="window.close()">Back</button>
        <button class="print-button" onclick="window.print()">
            Print / Save as PDF
        </button>
    </div>

    <main class="paper">
        <header class="header">
            <h1>{{ $settings->get('system_name', 'iSKEDYUL') }}</h1>
            <p>{{ $settings->get('campus_name', 'PUP Lopez Campus') }}</p>
            <p>{{ $settings->get('campus_address', '') }}</p>
        </header>

        <section class="document-title">
            <h2>Building Directory</h2>
            <p>Campus Building Inventory</p>
        </section>

        <div class="details">
            <div>
                <strong>Report:</strong> All Campus Buildings
            </div>

            <div>
                <strong>Date Printed:</strong> {{ now()->format('F d, Y') }}
                <br>
                <strong>Time Printed:</strong> {{ now()->format('h:i A') }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Building Code</th>
                    <th style="width: 30%;">Building Name</th>
                    <th>Description</th>
                    <th style="width: 12%;">Rooms</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($buildings as $building)
                    <tr>
                        <td>{{ $building->building_code }}</td>
                        <td>{{ $building->building_name }}</td>
                        <td>{{ $building->description ?: '—' }}</td>
                        <td>{{ $building->rooms_count }}</td>
                        <td class="status">{{ $building->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">
                            No buildings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            Total Buildings: {{ $buildings->count() }}
            <br>
            Total Rooms: {{ $buildings->sum('rooms_count') }}
        </div>

        <footer class="footer">
            Generated by {{ $settings->get('system_name', 'iSKEDYUL') }}
            — {{ $settings->get('campus_name', 'PUP Lopez Campus') }}
        </footer>
    </main>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 24px; color: #222; }
        h1 { text-align: center; color: #2c3e50; font-size: 20px; margin-bottom: 4px; }
        .subtitulo { text-align: center; font-size: 12px; color: #666; margin-top: 0; }
        .fecha { text-align: right; font-size: 11px; margin-bottom: 16px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #e9ecef; font-size: 11px; }
        td { font-size: 11px; }
        .text-center { text-align: center; }
        .total { text-align: right; font-weight: bold; margin-top: 16px; }
        .footer { margin-top: 24px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <p class="subtitulo">Social Networks</p>
    <p class="fecha">Generated: {{ $fecha }}</p>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px">#</th>
                <th>Group</th>
                <th>Creator</th>
                <th class="text-center">Privacy</th>
                <th class="text-center">Members</th>
                <th class="text-center">Status</th>
                <th class="text-center">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grupos as $grupo)
                <tr>
                    <td class="text-center">{{ $grupo->id }}</td>
                    <td>{{ $grupo->name }}</td>
                    <td>{{ $grupo->creator->name ?? 'No creator' }}</td>
                    <td class="text-center">{{ ucfirst($grupo->privacy) }}</td>
                    <td class="text-center">{{ $grupo->members_count }}</td>
                    <td class="text-center">{{ $grupo->status === 'active' ? 'Active' : 'Inactive' }}</td>
                    <td class="text-center">{{ $grupo->created_at?->format('m/d/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No groups found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total groups: {{ $grupos->count() }}</p>
    <p class="footer">Document generated automatically by the system.</p>
</body>
</html>
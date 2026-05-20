<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Candling</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #1f4b62; color: white; }
        .text-center { text-align: center; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #1f4b62; }
    </style>
</head>
<body>
    <div class="text-center title">Laporan Riwayat Candling Si-Tetas</div>
    <div class="text-center">Diekspor pada: {{ now()->format('d M Y H:i') }}</div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal/Waktu</th>
                <th>Nama Admin</th>
                <th>Hasil Prediksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $index => $history)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $history->created_at->format('d M Y H:i') }}</td>
                <td>{{ $history->admin_name }}</td>
                <td>
                    {{ $history->prediction_result }} ({{ $history->confidence_score }}%)<br>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('ml_egg_prediction.png'))) }}" alt="Hasil Prediksi" style="width: 100px; height: auto; margin-top: 5px; border-radius: 4px;">
                </td>
                <td>{{ $history->status }}</td>
            </tr>
            @endforeach
            
            @if($histories->isEmpty())
            <tr>
                <td colspan="5" class="text-center">Belum ada data riwayat candling.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>

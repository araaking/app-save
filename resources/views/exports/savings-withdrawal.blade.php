<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px;
        }
        .header h2 { 
            margin: 5px 0; 
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        td {
            padding: 8px;
            border: 1px solid #000;
        }
        .amount {
            text-align: right;
        }
        .section-title {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .total-row td {
            font-weight: bold;
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>MADRASAH DINIYAH TAKMILIYAH AWALIYAH</h2>
        <h3>RAUDLATUL MUTA'ALLIMIN CIBENCOY</h3>
        <p>CISAAT-SUKABUMI</p>
    </div>

    <table>
        <tr>
            <td>Nama</td>
            <td>{{ $bukuTabungan->siswa->name }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>{{ $bukuTabungan->siswa->kelas->name }}</td>
        </tr>
        <tr>
            <td>JUMLAH TABUNGAN</td>
            <td class="amount">Rp {{ number_format($bukuTabungan->total_simpanan, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <tr class="section-title">
            <td colspan="2">Biaya Admin</td>
        </tr>
        <tr>
            <td>Adm {{ $adminPercentage }}% ({{ $isEarlyWithdrawal ? 'Awal' : 'Akhir' }})</td>
            <td class="amount">Rp {{ number_format($adminFee, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <tr class="section-title">
            <td colspan="2">Potongan</td>
        </tr>
        <tr>
            <td>SPP ({{ 12 - (int)$unpaidMonths }} Bulan)</td>
            <td class="amount">Rp {{ number_format($deductions['SPP'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>IKK</td>
            <td class="amount">Rp {{ number_format($deductions['IKK'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Uang Pangkal</td>
            <td class="amount">Rp {{ number_format($deductions['Uang Pangkal'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>UAM</td>
            <td class="amount">Rp {{ number_format($deductions['UAM'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>THB</td>
            <td class="amount">Rp {{ number_format($deductions['THB'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Wisuda</td>
            <td class="amount">Rp {{ number_format($deductions['Wisuda'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Foto</td>
            <td class="amount">Rp {{ number_format($deductions['Foto'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Raport</td>
            <td class="amount">Rp {{ number_format($deductions['Raport'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunggakan Tahun Lalu</td>
            <td class="amount">Rp {{ number_format($deductions['previous_arrears'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pinjaman</td>
            <td class="amount">Rp {{ number_format($deductions['loan'], 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>JUMLAH POTONGAN</td>
            <td class="amount">Rp {{ number_format($deductions['total'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <tr class="total-row">
            <td>SISA TABUNGAN</td>
            <td class="amount">Rp {{ number_format($remainingSavings, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Cibencoy, {{ now()->translatedFormat('j F Y') }}</p>
        <p>TTD</p>
        <p>Bendahara</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tabungan Siswa</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      padding: 20px;
    }
    /* Container memastikan semua konten tampil dalam 1 halaman */
    .container {
      position: relative;
      min-height: 100vh;
    }
    .header {
      margin-bottom: 20px;
    }
    .title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
    }
    .student-info {
      margin-bottom: 15px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    td, th {
      border: 1px solid #ddd;
      padding: 12px;
    }
    .section-title {
      background-color: #f5f5f5;
      font-weight: bold;
    }
    .amount {
      text-align: right;
      font-family: 'Courier New', monospace;
    }
    .total-row {
      background-color: #e9e9e9;
      font-weight: bold;
    }
    /* Footer untuk TTD di pojok kanan bawah dengan ukuran font normal */
    .footer {
      position: absolute;
      bottom: 20px;
      right: 20px;
      text-align: right;
      /* Menghapus properti font-size agar menggunakan ukuran font default */
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header dengan judul dan informasi siswa -->
    <div class="header">
      <div class="title">TABUNGAN SISWA</div>
      <div class="student-info">
        Nama: {{ $bukuTabungan->siswa->name }}<br>
        Kelas: {{ $bukuTabungan->siswa->kelas->name }}<br>
        <!-- Tahun Ajaran bisa ditambahkan jika tersedia, misalnya: -->
        <!-- Tahun Ajaran: {{ $academicYear ?? 'N/A' }} -->
      </div>
    </div>

    <!-- Tabel rincian keuangan -->
    <table>
      <!-- Total Tabungan -->
      <tr>
        <td colspan="2" class="section-title">Total Tabungan</td>
      </tr>
      <tr>
        <td>Total Tabungan</td>
        <td class="amount">Rp {{ number_format($bukuTabungan->total_simpanan, 0, ',', '.') }}</td>
      </tr>

      <!-- Biaya Admin -->
      <tr>
        <td colspan="2" class="section-title">Biaya Admin</td>
      </tr>
      <tr>
        <td>Biaya Admin ({{ $adminPercentage }}%) ({{ $isEarlyWithdrawal ? 'Awal' : 'Akhir' }})</td>
        <td class="amount">Rp {{ number_format($adminFee, 0, ',', '.') }}</td>
      </tr>

      <!-- Rincian Potongan -->
      <tr>
        <td colspan="2" class="section-title">Rincian Potongan</td>
      </tr>
      <tr>
        <td>SPP ({{ 12 - (int)$unpaidMonths }} Bulan)</td>
        <td class="amount">Rp {{ number_format($deductions['SPP'], 0, ',', '.') }}</td>
      </tr>
      @if(isset($deductions['IKK']) && $deductions['IKK'] > 0)
      <tr>
        <td>IKK</td>
        <td class="amount">Rp {{ number_format($deductions['IKK'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['Uang Pangkal']) && $deductions['Uang Pangkal'] > 0)
      <tr>
        <td>Uang Pangkal</td>
        <td class="amount">Rp {{ number_format($deductions['Uang Pangkal'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['UAM']) && $deductions['UAM'] > 0)
      <tr>
        <td>UAM</td>
        <td class="amount">Rp {{ number_format($deductions['UAM'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['THB']) && $deductions['THB'] > 0)
      <tr>
        <td>THB</td>
        <td class="amount">Rp {{ number_format($deductions['THB'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['Wisuda']) && $deductions['Wisuda'] > 0)
      <tr>
        <td>Wisuda</td>
        <td class="amount">Rp {{ number_format($deductions['Wisuda'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['Foto']) && $deductions['Foto'] > 0)
      <tr>
        <td>Foto</td>
        <td class="amount">Rp {{ number_format($deductions['Foto'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['Raport']) && $deductions['Raport'] > 0)
      <tr>
        <td>Raport</td>
        <td class="amount">Rp {{ number_format($deductions['Raport'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['previous_arrears']) && $deductions['previous_arrears'] > 0)
      <tr>
        <td>Tunggakan Tahun Lalu</td>
        <td class="amount">Rp {{ number_format($deductions['previous_arrears'], 0, ',', '.') }}</td>
      </tr>
      @endif
      @if(isset($deductions['loan']) && $deductions['loan'] > 0)
      <tr>
        <td>Pinjaman</td>
        <td class="amount">Rp {{ number_format($deductions['loan'], 0, ',', '.') }}</td>
      </tr>
      @endif
      <!-- Total Potongan -->
      <tr class="total-row">
        <td>Total Potongan</td>
        <td class="amount">Rp {{ number_format($deductions['total'], 0, ',', '.') }}</td>
      </tr>
      <!-- Sisa Tabungan -->
      <tr class="total-row">
        <td>Sisa Tabungan</td>
        <td class="amount" style="color: #ff0000;">Rp {{ number_format($remainingSavings, 0, ',', '.') }}</td>
      </tr>
    </table>

    <!-- TTD/Signature di pojok kanan bawah dengan ukuran font normal -->
    <div class="footer">
      <p>Cibencoy, {{ now()->translatedFormat('j F Y') }}</p>
      <p>TTD</p>
      <p>Bendahara</p>
    </div>
  </div>
</body>
</html>

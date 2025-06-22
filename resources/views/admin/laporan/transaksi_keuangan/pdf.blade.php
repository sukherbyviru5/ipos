@extends('layout.kop')
@section('title')
    LAPORAN TRANSAKSI KEUANGAN PERPUSTAKAAN <br>{{ $kop->nama_madrasah ?? 'Nama Madrasah' }}
@endsection

@section('content')
    <div class="title">
        <h2>LAPORAN TRANSAKSI KEUANGAN PERPUSTAKAAN <br>{{ $kop->nama_madrasah ?? 'Nama Madrasah' }} </h2>
        <p>Bulan : {{ $monthName }} {{ $year }}</p>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th width="15%">Tanggal</th>
                    <th>Uraian</th>
                    <th width="15%">Debit</th>
                    <th width="15%">Kredit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $transaction->uraian }}</td>
                        <td>{{ $transaction->type === 'debit' ? 'Rp ' . number_format($transaction->nominal, 0, ',', '.') : '-' }}
                        </td>
                        <td>{{ $transaction->type === 'kredit' ? 'Rp ' . number_format($transaction->nominal, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Tidak ada data transaksi.</td>
                    </tr>
                @endforelse

                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Jumlah</strong></td>
                    <td><strong>Rp
                            {{ number_format($transactions->where('type', 'debit')->sum('nominal'), 0, ',', '.') }}</strong>
                    </td>
                    <td><strong>Rp
                            {{ number_format($transactions->where('type', 'kredit')->sum('nominal'), 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Saldo</strong></td>
                    <td colspan="2">
                        <strong>
                            Rp
                            {{ number_format($transactions->where('type', 'debit')->sum('nominal') - $transactions->where('type', 'kredit')->sum('nominal'), 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>
@endsection

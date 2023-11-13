<table>
    <thead>
        <tr>
            <th align="center" colspan="12" height="40">REKAPITULASI TAGIHAN SUB. KONTRAKTOR</th>
        </tr>
        <tr>
            <th align="center" colspan="12" height="40">PT. SAMUDRA AMERTA KONSTRUKSI</th>
        </tr>
        {!! str_repeat('<tr></tr>', 1) !!}
        <tr>
            <th colspan="2" height="30" align="left">
                PROJECT : TK. PULAU TIGA 3017
            </th>
        </tr>
        {!! str_repeat('<tr></tr>', 1) !!}
        <tr style="font-size: 8px;">
            <th height="40" style="border:20px medium black;">No.</th>
            <th height="40" style="border:20px medium black;" align="center">Pekerjaan</th>
            <th height="40" style="border:20px medium black;">Location</th>
            <th height="40" style="border:20px medium black;">Detail / Other</th>
            <th height="40" style="border:20px medium black;">Length (mm)</th>
            <th height="40" style="border:20px medium black;">Width (mm)</th>
            <th height="40" style="border:20px medium black;">Thick (mm)</th>
            <th height="40" style="border:20px medium black;">Qty/Days/%</th>
            <th height="40" style="border:20px medium black;">Amount</th>
            <th height="40" style="border:20px medium black;">Unit</th>
            <th colspan="2" align="center" height="40" style="border:20px medium black;">Tagihan Subcont</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr style="font-size: 8px;">
                <td align="center" style="border-right: 20px medium black;border-left: 20px medium black;" height="30"><strong>B</strong></td>
                <td colspan="9" style="border: 20px medium black;font-size: 8px;" height="30"><strong>{{ $key }}</strong></td>
                <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
            </tr>
            @php
                $total = 0;
                $prevTotal = 0;
                $subKategori = '';
                $prevSub = '';
                $prevKodeUnik = '';
            @endphp
            @foreach ($item as $value)
            @php
                $prevTotal = $value->amount * $value->harga_vendor;
                $subkategori = $value->subKategori->name;
                $kodeUnik = $value->kode_unik;
            @endphp
                @if ($prevKodeUnik !== $kodeUnik)
                    <tr>
                        <td height="30" align="center" style="border-right: 20px medium black;border-left: 20px medium black;">{{ $loop->iteration }}</td>
                        <td height="30" style="font-size: 8px;">
                            @if ($subkategori === 'Telah dilaksanakan pekerjaan')
                                <strong>{{ $value->subKategori->name }} {{ $value->deskripsi_subkategori }}</strong>
                            @else
                                <strong>{{ $value->subKategori->name }}</strong>
                            @endif
                        </td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    </tr>
                @endif
                <tr>
                    <td align="center" height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px; "></td>
                    <td height="30" align="left" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->pekerjaan->name ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->id_lokasi ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->detail ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->length ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->width ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->thick ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->qty ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->amount ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->unit ?? ' ' }}</td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">
                       Rp. {{ number_format($value->harga_vendor, 0, ',', '.') }}
                    </td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">
                        Rp. {{ number_format(($value->amount * $value->harga_vendor), 0, ',', '.') }}
                    </td>
                </tr>
            @php
                $prevTotal = $value->amount * $value->harga_vendor;
                $total = $total + $prevTotal;
                $prevSub = $subKategori;
                $prevKodeUnik = $kodeUnik;
            @endphp
            @endforeach
        @endforeach
        <tr>
            <td style="border: 20px medium black;"></td>
            <td colspan="7" style="border: 20px medium black"></td>
            <td colspan="3" align="center" height="20" style="border: 20px medium black"><strong>TOTAL</strong></td>
            <td height="30" style="border: 20px medium black;" align="left"><strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></td>
        </tr>
        {!! str_repeat('<tr></tr>', 2) !!}
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td align="center">Dibuat Oleh,</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3">Diperiksa / Disetujui oleh,</td>
            <td></td>
        </tr>
        {!! str_repeat('<tr></tr>', 4) !!}
        <tr>
            <td></td>
            <td align="center">Makrumah</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3">Hanafi Santoso</td>
            <td></td>
        </tr>
        {!! str_repeat('<tr></tr>', 2) !!}
        <tr height="30">
            <td>Catatan : </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr height="30">
            <td></td>
            <td colspan="11">Pengajuan Komplain Selambat - lambatnya 2 hari setelah Tanggal ACC (penyetujuan) oleh Pak Hanafi, Jika melebihi waktu yang ditentukan maka dianggap Setuju dengan Nilai di Atas.</td>
        </tr>
        <tr height="30">
            <td></td>
            <td colspan="11">Subcont Wajib Mengembalikan File yang Asli Selambat - lambatnya 2 hari setelah tanggal ACC (Penyetujuan) oleh Pak Hanafi.</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="11">Jika File Asli Tidak Kembali maka Subcont Dianggap Tidak Menagih.</td>
        </tr>
    </tfoot>
</table>

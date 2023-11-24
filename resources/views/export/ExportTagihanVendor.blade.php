<table>
    <thead>
        <tr>
            <th align="center" colspan="12" height="40">REKAPITULASI TAGIHAN SUB. KONTRAKTOR</th>
        </tr>
        <tr>
            <th align="center" colspan="12" height="40">{{ strtoupper($title) }}</th>
        </tr>
        {!! str_repeat('<tr></tr>', 1) !!}
        <tr>
            <th colspan="4" height="30" align="left">
                PROJECT : {{ $name->nama_project }}
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
        @php
            $letters = range('A', 'Z');
            $total = 0;
            $subCount = 1;
            $count = 0;
            $prevTotal = 0;
            $totalNow = 0;
            $prevIndex = '';
            $nowKategori = '';
            $prevKategori = '';
        @endphp
        @foreach ($data as $key => $datas)
            @if($datas->count() > 0)
                <tr style="font-size: 8px;">
                    <td align="center" style="border-right: 20px medium black;border-left: 20px medium black;" height="30"><strong>{{ getLatters($key) }}</strong></td>
                    <td colspan="9" style="border: 20px medium black;font-size: 8px;" height="30"><strong>{{ $key }}</strong></td>
                    <td style="border: 20px medium black;border-left: 20px medium black; font-size: 8px;" align="center">Harga</td>
                    <td style="border: 20px medium black;border-left: 20px medium black; font-size: 8px;" align="center">Jumlah</td>
                </tr>
            @endif
            @php
                $subKategori = '';
                $prevSub = '';
                $prevKodeUnik = '';
            @endphp
            @foreach ($datas as $keys => $item)
                @foreach ($item as $value)
                    @php
                        $totalNow = $value->amount * $value->harga_vendor;
                        if ($value->amount && $value->harga_vendor) {
                            $totalNow = $value->amount * $value->harga_vendor;
                        } else {
                            $totalNow = 0;
                        }
                        $subkategori = $value->subKategori->name;
                        $kodeUnik = $value->kode_unik;
                        $nowKategori = $key;
                    @endphp
                    @if ($prevKodeUnik !== $kodeUnik)
                        <tr style="font-size: 8px;">
                            <td class="text-center" height="20" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                            <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
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
                        @php
                            if ($prevKategori !== $key) {
                                $subCount = 1;
                            } else {
                                $subCount++;
                            }
                        @endphp
                        <tr>
                            <td height="30" align="center" style="border-right: 20px medium black;border-left: 20px medium black;">{{ $subCount }}</td>
                            <td height="30" style="font-size: 8px;">
                                @if (strtolower($subkategori) === 'telah dilaksanakan pekerjaan')
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
                        <td height="30" align="left" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->pekerjaan ? ($value->pekerjaan->name ? ($value->deskripsi_pekerjaan ? $value->pekerjaan->name . ' ' . $value->deskripsi_pekerjaan : $value->pekerjaan->name) : '') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="center">{{ $value->id_lokasi ?? ' ' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="center">{{ $value->detail ?? ' ' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->length ? number_format($value->length,2, ',','') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->width ? number_format($value->width,2, ',','') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->thick ? number_format($value->thick,2, ',','') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->qty ? number_format($value->qty,2, ',','') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->amount ? number_format($value->amount,2, ',','') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="right">{{ $value->unit ?? ' ' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="right">
                        Rp. {{ number_format($value->harga_vendor, 0, ',', '.') }}
                        </td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="right">
                            Rp. {{ number_format(($value->amount * $value->harga_vendor), 0, ',', '.') }}
                        </td>
                    </tr>
                    @php
                        $prevTotal = $totalNow;
                        $prevIndex = $key;
                        $total +=$prevTotal;
                        $prevSub = $subKategori;
                        $prevKodeUnik = $kodeUnik;
                        $prevKategori =  $key;
                    @endphp
                @endforeach
            @endforeach

            @php
                $count++
            @endphp
        @endforeach
        <tr>
            <td style="border: 20px medium black;"></td>
            <td colspan="7" style="border: 20px medium black"></td>
            <td colspan="3" align="center" height="20" style="border: 20px medium black"><strong>TOTAL</strong></td>
            <td height="30" style="border: 20px medium black;" align="rigth"><strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></td>
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
            <td colspan="2">Catatan : </td>
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

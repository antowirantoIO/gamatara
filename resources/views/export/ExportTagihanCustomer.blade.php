<table>
    <thead>
        <tr>
            <th colspan="12" height="100"></th>
        </tr>
        <tr>
            <th colspan="12" height="30"><strong>PT. GAMATARA TRANS OCEAN SHIPYARD</strong></th>
        </tr>
        <tr>
            <th colspan="12" height="30">Kantor : Jl Tanjung Tengah No 1B</th>
        </tr>
        <tr>
            <th colspan="12" height="30">Telp (0231) 226435 Fax (0231) 226436</th>
        </tr>
        <tr>
            <th colspan="12" height="30">Pelabuhan Cirebon 45122 - Jawa Barat</th>
        </tr>
        <tr>
            <th colspan="12" height="30" style="border: 20px medium black;"><strong>TAGIHAN BIAYA DOCKING</strong></th>
        </tr>
        <tr>
            <th colspan="12" height="30" style="border: 20px medium black;"><strong>{{ $name->nama_project }}</strong></th>
        </tr>
        <tr>
            <th colspan="12" height="30"></th>
        </tr>
        <tr>
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
            <th colspan="2" align="center" height="40" style="border:20px medium black;">Tagihan Customers</th>
        </tr>
    </thead>
    <tbody>
        @php
            $letters = range('A', 'Z');
            $subCount = 1;
            $count = 0;
            $prevIndex = '';
            $total = 0;
            $prevTotal = 0;
            $totalNow = 0;
            $loops = false;
            $nowKategori = '';
            $prevKategori = '';
        @endphp
        @foreach ($data as $key => $datas)
            @if($datas->count() > 0)
                <tr style="font-size: 8px;">
                    <td align="center" style="border: 20px medium black;" height="30"><strong>{{ getLatters($key) }}</strong></td>
                    <td colspan="9" style="border: 20px medium black;font-size: 8px;" height="30"><strong>{{ $key }}</strong></td>
                    @if (!$loops)
                        <td style="border-right: 20px medium black;border-left: 20px medium black;" align="center">Harga</td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;" align="center">Jumlah</td>
                    @else
                        <td style="border-right: 20px medium black;border-left: 20px medium black;" align="center"></td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;" align="center"></td>
                    @endif
                </tr>
            @endif
            @php
                $subKategori = '';
                $prevSub = '';
                $prevKodeUnik = '';
            @endphp
            @foreach ($datas as $keys => $item)
                <tr style="font-size: 8px;">
                    <td class="text-center" height="30" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border: 20px medium black;"></td>
                    <td style="border: 20px medium black;"></td>
                </tr>
                <tr>
                    <td height="30" align="center" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $loop->iteration }}</td>
                    <td height="30" style="font-size: 8px;">&nbsp;
                        <strong>{{ $keys }}</strong></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border: 20px medium black;"></td>
                    <td style="border: 20px medium black;"></td>
                </tr>
                @foreach ($item as $indexs => $value)
                @php
                    $totalNow = $value->amount * $value->pekerjaan->harga_customer;
                    if ($value->amount && $value->pekerjaan->harga_customer) {
                        $totalNow = $value->amount * $value->pekerjaan->harga_customer;
                    } else {
                        $totalNow = 0;
                    }
                @endphp
                    <tr>
                        <td align="center" height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px; "></td>
                        <td height="30" align="left" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">&nbsp;{{ $value->pekerjaan_concat ?? ' ' }}</td>
                        <td style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="center">{{ $value->id_lokasi ?? ' ' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="center">{{ $value->detail ?? ' ' }}</td>
                        <td height="30" align="right" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->length ? number_format($value->length,2, ',','') : '' }}</td>
                        <td height="30" align="right" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->width ? number_format($value->width,2, ',','') : '' }}</td>
                        <td height="30" align="right" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->thick ? number_format($value->thick,2, ',','') : '' }}</td>
                        <td height="30" align="right" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->qty ? number_format($value->qty,2, ',','') : '' }}</td>
                        <td height="30" align="right" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->amount ? number_format($value->amount,2, ',','') : '' }}</td>
                        <td height="30" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;" align="right">{{ $value->unit ?? ' ' }}</td>
                        <td height="30" style="border: 20px medium black;" align="right">
                        Rp. {{ number_format($value->pekerjaan->harga_customer, 0, ',', '.') }}
                        </td>
                        <td height="30" style="border: 20px medium black;" align="right">
                            Rp. {{ number_format(($value->amount * $value->pekerjaan->harga_customer), 0, ',', '.') }}
                        </td>
                        <td height="30">{{ $value->vendors->name ?? '-' }}</td>
                    </tr>
                    @php
                        $prevTotal = $totalNow;
                        $prevIndex = $key;
                        $total += $prevTotal;
                    @endphp
                @endforeach
                @php
                    $count++;
                    $loops = true;
                @endphp
            @endforeach
        @endforeach
        <tr>
            <td style="border: 20px medium black;"></td>
            <td colspan="7" style="border: 20px medium black"></td>
            <td colspan="3" align="center" height="30" style="border: 20px medium black"><strong>TOTAL</strong></td>
            <td height="30" style="border: 20px medium black;" align="right"><strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></td>
        </tr>
        {!! str_repeat('<tr></tr>', 2) !!}
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5" align="center"><strong>Hormat Kami</strong></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5"  align="center"><strong>Cirebon, {{ formatTanggal() }}</strong></td>
        </tr>
        {!! str_repeat('<tr></tr>', 5) !!}
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5" align="center"><strong>HANAFI SANTOSO, ST</strong></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5" align="center">DIREKTUR</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5" align="center">PT. GAMATARA TRANS OCEAN SHIPYARD</td>
        </tr>
    </tfoot>
</table>

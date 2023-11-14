<table>
    <thead>
        <tr>
            <th colspan="12" height="100"></th>
        </tr>
        <tr>
            <th colspan="12" height="20"><strong>PT. GAMATARA TRANS OCEAN SHIPYARD</strong></th>
        </tr>
        <tr>
            <th colspan="12" height="20">Kantor : Jl Tanjung Tengah No 1B</th>
        </tr>
        <tr>
            <th colspan="12" height="20">Telp (0231) 226435 Fax (0231) 226436</th>
        </tr>
        <tr>
            <th colspan="12" height="20">Pelabuhan Cirebon 45122 - Jawa Barat</th>
        </tr>
        <tr>
            <th colspan="12" height="30" style="border: 20px medium black;"><strong>TAGIHAN BIAYA DOCKING</strong></th>
        </tr>
        <tr>
            <th colspan="12" height="30" style="border: 20px medium black;"><strong>{{ $name->nama_project }}</strong></th>
        </tr>
        <tr>
            <th colspan="12" height="20"></th>
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
            //  dd($data);
        @endphp
        @foreach ($data as $key => $item)
            @if($item->count() > 0)
                <tr style="font-size: 8px;">
                    <td align="center" style="border: 20px medium black;" height="30"><strong>{{ $letters[$count] }}</strong></td>
                    <td colspan="9" style="border: 20px medium black;font-size: 8px;" height="30"><strong>{{ $key }}</strong></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                    <td style="border-right: 20px medium black;border-left: 20px medium black;"></td>
                </tr>
            @endif
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
                @if ($prevKodeUnik != $kodeUnik)
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
                        <td style="border: 20px medium black;"></td>
                        <td style="border: 20px medium black;"></td>
                    </tr>
                    <tr>
                        <td height="20" align="center" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $subCount }}</td>
                        <td height="20" style="font-size: 8px;">
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
                        <td style="border: 20px medium black;"></td>
                        <td style="border: 20px medium black;"></td>
                    </tr>
                    @php
                        if ($value->kategori->name !== $key) {
                            $subCount = 1;
                        } else {
                            $subCount++;
                        }
                    @endphp
                @endif
                <tr>
                    <td align="center" height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px; "></td>
                    <td height="20" align="left" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->pekerjaan->name ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->id_lokasi ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->detail ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->length ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->width ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->thick ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->qty ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->amount ?? ' ' }}</td>
                    <td height="20" style="border-right: 20px medium black;border-left: 20px medium black;font-size: 8px;">{{ $value->unit ?? ' ' }}</td>
                    <td height="20" style="border: 20px medium black;" >
                       Rp. {{ number_format($value->harga_customer, 0, ',', '.') }}
                    </td>
                    <td height="20" style="border: 20px medium black;">
                        Rp. {{ number_format(($value->amount * $value->harga_customer), 0, ',', '.') }}
                    </td>
                </tr>
                @php
                    $prevTotal = $value->amount * $value->harga_customer;
                    $prevIndex = $key;
                    $total = $total + $prevTotal;
                    $prevSub = $subKategori;
                    $prevKodeUnik = $kodeUnik;
                @endphp
            @endforeach
            @php
                $count++
            @endphp
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5"><strong>Hormat Kami</strong></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5"><strong>Cirebon, {{ formatTanggal() }}</strong></td>
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
            <td colspan="5"><strong>HANAFI SANTOSO, ST</strong></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5">DIREKTUR</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="5">PT. GAMATARA TRANS OCEAN SHIPYARD</td>
        </tr>
    </tfoot>
</table>

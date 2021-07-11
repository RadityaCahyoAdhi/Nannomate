<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fossil List</title>
</head>
<body>
    <table> {{-- yellow: #fffe00 --}}
        <tr>
            <td align="center" colspan="6"><b>LABORATORIUM PALEONTOLOGI</b></td>
            <td align="center" colspan="23" style="border: 1px solid black; background: #eeece0;"><b>LOKASI</b></td>
            <td align="center" colspan="9" style="border: 1px solid black; background: #eeece0;"><b>LITOLOGI</b></td>
            <td align="center" colspan="7" style="border: 1px solid black; background: #eeece0;"><b>LONGITUDE</b></td>
            <td align="center" colspan="7" style="border: 1px solid black; background: #eeece0;"><b>LATITUDE</b></td>
        </tr>
        <tr>
            <td align="center" colspan="6"><b>JURUSAN TEKNIK GEOLOGI</b></td>
            <td align="center" valign="center" colspan="23" rowspan="3" style="border: 1px solid black">{{$studi_area['lokasi']}}</td>
            <td align="center" valign="center" colspan="9" rowspan="3" style="border: 1px solid black">{{$studi_area['litologi']}}</td>
            <td align="center" valign="center" colspan="7" rowspan="3" style="border: 1px solid black">{{$studi_area['longitude']}}</td>
            <td align="center" valign="center" colspan="7" rowspan="3" style="border: 1px solid black">{{$studi_area['latitude']}}</td>
        </tr>
        <tr>
            <td align="center" colspan="6"><b>FAKULTAS TEKNIK</b></td>
        </tr>
        <tr>
            <td align="center" colspan="6"><b>UNIVERSITAS GADJAH MADA</b></td>
        </tr>
        <tr>
            <td align="center" valign="top" colspan="6" style="border: 1px solid black; background: #eeece0;"><b>JENIS FOSIL YANG DIPERIKSA:<br></b></td>
            <td align="center" valign="top" colspan="15" style="border: 1px solid black; background: #eeece0;"><b>FORMASI</b></td>
            <td align="center" valign="top" colspan="16" style="border: 1px solid black; background: #eeece0;"><b>NO. SAMPEL</b></td>
            <td align="center" valign="top" colspan="15" style="border: 1px solid black; background: #eeece0;"><b>JENIS PENELITIAN</b></td>
        </tr>
        <tr>
            <td align="center" colspan="6" style="border: 1px solid black">NANNOFOSIL</td>
            <td align="center" colspan="15" style="border: 1px solid black">{{$studi_area['formasi']}}</td>
            <td align="center" colspan="16" style="border: 1px solid black">{{$sample['stopsite']}}</td>
            <td align="center" colspan="15" style="border: 1px solid black">{{$sample['tujuan']}}</td>
        </tr>
        <tr>
            <td align="center" colspan="6" style="border: 1px solid black; background: #eeece0;"><b>PREPARASI CONTOH:</b></td>
            <td align="center" valign="top" colspan="15" style="border: 1px solid black; background: #eeece0;"><b>TANGGAL</b></td>
            <td align="center" valign="top" colspan="31" style="border: 1px solid black; background: #eeece0;"><b>PEMERIKSA</b></td>
        </tr>
        <tr>
            @if ($sample['preparasi'] == 'Ayakan')
                <td align="center" colspan="2" style="border: 1px solid black; background: #fffe00;">Ayakan</td>
            @else
                <td align="center" colspan="2" style="border: 1px solid black">Ayakan</td>
            @endif
            @if ($sample['preparasi'] == 'Asahan')
                <td align="center" colspan="2" style="border: 1px solid black; background: #fffe00;">Asahan</td>
            @else
                <td align="center" colspan="2" style="border: 1px solid black">Asahan</td>
            @endif
            @if ($sample['preparasi'] == 'Smear')
                <td align="center" style="border: 1px solid black; background: #fffe00;">Smear</td>
            @else
                <td align="center" style="border: 1px solid black">Smear</td>
            @endif
            @if ($sample['preparasi'] == 'Lain')
                <td align="center" style="border: 1px solid black; background: #fffe00;">Lain</td>
            @else
                <td align="center" style="border: 1px solid black">Lain</td>
            @endif
            <td align="center" colspan="15" style="border: 1px solid black">{{Carbon\Carbon::parse($observer['tanggal_penelitian'])->translatedFormat('d F Y')}}</td>
            <td align="center" colspan="31" style="border: 1px solid black">{{$observer['nama_observer']}}</td>
        </tr>
        <tr>
            <td align="center" colspan="6" style="border: 1px solid black; background: #eeece0;"><b>KELIMPAHAN</b></td>
            <td align="center" colspan="46" style="border: 1px solid black; background: #eeece0;"><b>KESIMPULAN</b></td>
        </tr>
        <tr>
            @if ($sample['kelimpahan'] == 'Kosong')
                <td align="center" colspan="2" style="border: 1px solid black; background: #fffe00;">Kosong</td>
            @else
                <td align="center" colspan="2" style="border: 1px solid black">Kosong</td>
            @endif
            @if ($sample['kelimpahan'] == 'Jarang')
                <td align="center" style="border: 1px solid black; background: #fffe00;">Jarang</td>
            @else
                <td align="center" style="border: 1px solid black">Jarang</td>
            @endif
            @if ($sample['kelimpahan'] == 'Beberapa')
                <td align="center" style="border: 1px solid black; background: #fffe00;">Beberapa</td>
            @else
                <td align="center" style="border: 1px solid black">Beberapa</td>
            @endif
            @if ($sample['kelimpahan'] == 'Umum')
                <td align="center" style="border: 1px solid black; background: #fffe00;">Umum</td>
            @else
                <td align="center" style="border: 1px solid black">Umum</td>
            @endif
            @if ($sample['kelimpahan'] == 'Melimpah')
                <td align="center" style="border: 1px solid black; background: #fffe00;">Melimpah</td>
            @else
                <td align="center" style="border: 1px solid black">Melimpah</td>
            @endif
            <td align="center" valign="top" colspan="46" style="border: 1px solid black; background: #eeece0;"><b>ZONA/UMUR:</b></td>
        </tr>
        <tr>
            <td align="center" colspan="6" style="border: 1px solid black; background: #eeece0;"><b>PENGAWETAN FOSIL PADA UMUMNYA</b></td>
            <td align="center" valign="center" colspan="46" rowspan="2" style="border: 1px solid black">
                @if ($kesimpulan['min_zona'] != $kesimpulan['max_zona'])
                    {{$kesimpulan['min_zona']}}-{{$kesimpulan['max_zona']}}
                @else
                    {{$kesimpulan['min_zona']}}
                @endif
                @if ($kesimpulan['min_umur'] == $kesimpulan['max_umur'])
                    ({{$kesimpulan['min_umur']}})
                @else
                    @if ($kesimpulan['min_umur_kata_per_kata'][0] != $kesimpulan['max_umur_kata_per_kata'][0])
                        ({{$kesimpulan['min_umur']}}-{{$kesimpulan['max_umur']}})
                    @else
                        ({{$kesimpulan['min_umur']}}-{{$kesimpulan['max_umur_kata_per_kata'][1]}})
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            @if ($sample['pengawetan'] == 'Jelek')
                <td align="center" colspan="2" style="border: 1px solid black; background: #fffe00;">Jelek</td>
            @else
                <td align="center" colspan="2" style="border: 1px solid black">Jelek</td>
            @endif
            @if ($sample['pengawetan'] == 'Sedang')
                <td align="center" colspan="2" style="border: 1px solid black; background: #fffe00;">Sedang</td>
            @else
                <td align="center" colspan="2" style="border: 1px solid black">Sedang</td>
            @endif
            @if ($sample['pengawetan'] == 'Bagus')
                <td align="center" colspan="2" style="border: 1px solid black; background: #fffe00;">Bagus</td>
            @else
                <td align="center" colspan="2" style="border: 1px solid black">Bagus</td>
            @endif
        </tr>
        <tr>
            <td align="center" colspan="6" style="border: 1px solid black"></td>
            <td align="center" colspan="46" style="border: 1px solid black; background: #eeece0;"><b>ZONASI (Martini, 1971)</b></td>
        </tr>
        <tr>
            <td align="center" valign="center" rowspan="3" style="border: 1px solid black; background: #eeece0;"><b>Jumlah</b></td>
            <td align="center" valign="center" colspan="5" rowspan="3" style="border: 1px solid black; background: #eeece0;"><b>Spesies</b></td>
            <td align="center" colspan="9" style="border: 1px solid black"><b>Paleosen</b></td>
            <td align="center" colspan="11" style="border: 1px solid black"><b>Eosen</b></td>
            <td align="center" colspan="5" style="border: 1px solid black"><b>Oligosen</b></td>
            <td align="center" colspan="12" style="border: 1px solid black"><b>Miosen</b></td>
            <td align="center" colspan="7" style="border: 1px solid black"><b>Pliosen</b></td>
            <td align="center" colspan="2" style="border: 1px solid black"><b>Pleistosen</b></td>
        </tr>
        <tr>
            <td align="center" colspan="4" style="border: 1px solid black"><b>E</b></td>
            <td align="center" colspan="5" style="border: 1px solid black"><b>L</b></td>
            <td align="center" colspan="5" style="border: 1px solid black"><b>E</b></td>
            <td align="center" colspan="3" style="border: 1px solid black"><b>M</b></td>
            <td align="center" colspan="3" style="border: 1px solid black"><b>L</b></td>
            <td align="center" colspan="2" style="border: 1px solid black"><b>E</b></td>
            <td align="center" colspan="2" style="border: 1px solid black"><b>M</b></td>
            <td align="center" colspan="1" style="border: 1px solid black"><b>L</b></td>
            <td align="center" colspan="5" style="border: 1px solid black"><b>E</b></td>
            <td align="center" colspan="4" style="border: 1px solid black"><b>M</b></td>
            <td align="center" colspan="3" style="border: 1px solid black"><b>L</b></td>
            <td align="center" colspan="3" style="border: 1px solid black"><b>E</b></td>
            <td align="center" colspan="4" style="border: 1px solid black"><b>L</b></td>
            <td align="center" colspan="2" style="border: 1px solid black"><b></b></td>
        </tr>
        <tr>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP1</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP2</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP3</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP4</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP5</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP6</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP7</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP8</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP9</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP10</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP11</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP12</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP13</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP14</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP15</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP16</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP17</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP18</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP19</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP20</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP21</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP22</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP23</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP24</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NP25</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN1</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN2</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN3</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN4</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN5</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN6</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN7</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN8</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN9</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN10</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN11</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN12</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN13</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN14</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN15</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN16</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN17</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN18</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN19</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN20</b></td>
            <td align="center" valign="center" style="border: 1px solid black"><b>NN21</b></td>
        </tr>
        <tr>
            <td align="center" colspan="52" style="border: 1px solid black; background: #eeece0;"><b>NANNOFOSIL</b></td>
        </tr>
        @foreach ($spesies_nanofosil as $spesies_nanofosil_value)
        <tr>
            <td align="center" style="border: 1px solid black;">{{$sample_spesies[$i]['jumlah']}}</td>
            <td colspan="5" style="border: 1px solid black;">{{$spesies_nanofosil_value[0]['nama_spesies']}}</td>
            @if ($umur_condition[$j]['NP1'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP2'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP3'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP4'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP5'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP6'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP7'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP8'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP9'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP10'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP11'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP12'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP13'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP14'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP15'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP16'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP17'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP18'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP19'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP20'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP21'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP22'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP23'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP24'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NP25'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN1'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN2'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN3'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN4'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN5'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN6'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN7'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN8'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN9'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN10'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN11'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN12'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN13'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN14'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN15'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN16'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN17'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN18'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN19'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN20'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
            @if ($umur_condition[$j]['NN21'] == true)
                <td style="border: 1px solid black; background: #fffe00;"></td>
            @else
                <td style="border: 1px solid black"></td>
            @endif
        </tr>
        @php
            $i++;
            $j++;
        @endphp
        @endforeach
    </table>
</body>
</html>

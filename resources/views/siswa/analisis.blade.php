@extends('template')
@section('title', 'Analisis Jawaban - BaseCampTO ')

@section('intro-header')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endsection
@section('main')
<div class="col-12 row row-imbang primary" style="margin-top:60px;">
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12 align-items-lg-center">
            <p class="text-center"><img class=" text-center rounded-circle" alt="100x100" src="https://place-hold.it/100x100/e74c3c/ecf0f1?text={{Str::substr(Auth::user()->name,0,1)}}&fontsize=55" data-holder-rendered="true"></p>
            <p class="text-center h4 text-danger text-capitalize">{{ Auth::user()->name }}</p>
            <hr>
            <p class="text-center text-uppercase h4 text-primary">TRY OUT {{$tps->nama}}</p>

        </div>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-danger"><i class="fa fa-bar-chart"></i> Grafik Nilai</p>
            <hr>
            <div class="col-12 row row-imbang">
                <div class="col-md-6 col-12">
                    <canvas id="bar-chart" width="800" height="600"></canvas>
                    <hr>
                    <p class="h6 text-center">Detail Score TPS<p>
                    <table class="table table-striped text-center table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sesi</th>
                                <th>B</th>
                                <th>S</th>
                                <th>SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $nod=1;
                        $detail_nilai=0;
                        ?>
                        @foreach($detai as $detail)
                            <tr>
                                <td class="text-center">{{$nod}}</td>
                                <td class="text-left">{{$detail->nama_sesi}}</td>
                                <td class="bg-success text-center text-white">{{$detail->benar}}</td>
                                <td class="bg-warning text-center">{{$detail->salah}}</td>
                                <td class="text-center">{{$detail->nilai}}</td>
                            </tr>
                            <?php
                            $detail_nilai+=$detail->nilai;
                            $nod++;
                            ?>
                        @endforeach
                            <tr>
                                <td colspan="4" class="font-weight-bold">Total SCORE TPS</td>
                                <td class="font-weight-bold">{{$detail_nilai}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 col-12 w-100">
                    <canvas id="bar-chart-tka" width="800" height="600"></canvas>
                    <hr>
                    <p class="h6 text-center">Detail Score TKA<p>
                    <table class="table table-striped text-center table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sesi</th>
                                <th>B</th>
                                <th>S</th>
                                <th>SCORE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $nod2=1;
                        $detail_nilai2=0;
                            ?>
                            @foreach($detai2 as $detail2)
                            <tr>
                                <td class="text-center"><?php echo $nod2;?></td>
                                <td class="text-left">{{$detail2->nama_sesi}}</td>
                                <td class="bg-success text-center text-white">{{$detail2->benar}}</td>
                                <td class="bg-warning text-center">{{$detail2->salah}}</td>
                                <td class="text-center">{{$detail2->nilai}}</td>
                            </tr>
                            <?php
                            $detail_nilai2+=$detail2->nilai;
                            $nod2++;
                            ?>
                            @endforeach
                            <tr>
                                <td colspan="4" class="font-weight-bold">Total SCORE TKA</td>
                                <td class="font-weight-bold">{{$detail_nilai2}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
        <p class="h4 text-danger"><i class="fa fa-bar-chart"></i> My Rank</p>
        <hr>
        <?php
                $my=DB::select("SELECT ps FROM ( select @rownum:=@rownum+1 ps, p.* from peringkat p, (SELECT @rownum:=0) r where id_paket='$id_pakett' order by nilai desc) s WHERE id_siswa = '$id' and id_paket='$id_pakett'");
                //$myr=mysqli_fetch_assoc($my);
                $myr=json_decode(json_encode($my),true);
            ?>
            <p class="h3 text-warning text-center">YOUR RANK</p>
            <p class="display-2 text-danger font-weight-bold text-center">{{$myr[0]['ps']}}</p>
            <p class="h4 text-warning text-center">Total Score : {{$myscore->nilai}}</p>
            <p class="h4 text-primary"><i class="fa fa-bar-chart"></i> Top 30 Global Rank</p>
            <hr>
            <div class="col-12 justify-content-center">
            </div>
            <div class="col-12 row row-imbang">
                <?php
                    $offset=array("0","10","20");
                    $nrengking=1;
                    for($p=0;$p<3;$p++){
                        $of=$offset[$p];
                        ?>
                        <div class="col-md-4 col-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Sekolah</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
        
                            <?php
                            $re=DB::table('peringkat')->join('users','users.id','=','peringkat.id_siswa')->select('peringkat.id_siswa','peringkat.id_paket','peringkat.nilai','users.name','users.id','users.sekolah')->where('peringkat.id_paket',$id_pakett)->orderBy('peringkat.nilai','DESC')->skip($of)->take(10);
                            if($p < 4){
                                $ren=$re->get();
                                ?>
                                @foreach($ren as $rengking)
                                    <tr>
                                        <td class="text-center">{{$nrengking}}</td>
                                        <td class="text-center">{{$rengking->name}}</td>
                                        <td class="text-center">{{$rengking->sekolah}}</td>
                                        <td class="text-center">{{$rengking->nilai}}</td>
                                    </tr>
                                    <?php
                                    $nrengking++;
                                ?>
                                @endforeach
                                <?php
                            }
                            ?>
                                </tbody>
                            </table>  
                        </div>
                        <?php
                    }
                ?>
            </div> 
        </div>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-success"><i class="fa fa-bar-chart"></i> Analisis Jawaban</p>
            <hr>   
            <div class="col-12 row row-imbang">
                @foreach($an as $ana)
                <div class="col-md-4 col-12">
                    <p class="h5 text-center">{{$ana->nama_sesi}}</p>
                    <table class="table table-striped text-center table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jawaban</th>
                                <th>Kunci</th>
                                <th>% Benar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $noa=1;
                            $jana=DB::table('user_jawaban')->join('soal','soal.id','=','user_jawaban.id_soal')->select('user_jawaban.jawabanSiswa','user_jawaban.id_sesi','user_jawaban.id_siswa','user_jawaban.kunci','user_jawaban.id_soal','soal.id','soal.menjawab_benar')->where('user_jawaban.id_siswa',$id)->where('user_jawaban.id_sesi',$ana->id)->get();
                            ?>
                            @foreach($jana as $janal)
                                <tr>
                                    <td class="text-center">{{$noa}}</td>
                                    <td class="text-center @if($janal->jawabanSiswa==$janal->kunci){{'bg-success'}}@else{{ 'bg-warning'}}@endif">{{$janal->jawabanSiswa}}</td>
                                    <td class="text-center">{{$janal->kunci}}</td>
                                    <td class=" text-center">{{number_format((float)$janal->menjawab_benar, 2, '.', '')}}</td>
                                </tr>
                                <?php
                                $noa++;
                                ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script>  
        // Bar chart
        new Chart(document.getElementById("bar-chart"), {
            type: 'bar',
            data: {
            labels: ["PU","PBM","PPU","PK"],
            datasets: [
                {
                label: "Score",
                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9"],
                data: <?php echo json_encode($nilaie);?>
                }
            ]
            },
            options: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Tes Potensi Skolastik'
            }
            }
        });
        new Chart(document.getElementById("bar-chart-tka"), {
            type: 'bar',
            data: {
            labels: ["MAT","FIS","KIM","BIO"],
            datasets: [
                {
                label: "Score",
                backgroundColor: ["#f1c40f", "#3498db","#34495e","#2ecc71"],
                data: <?php echo json_encode($nilaie2);?>
                }
            ]
            },
            options: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Tes Potensi Akademik'
            }
            }
        });
    </script>  
</div>
@endsection
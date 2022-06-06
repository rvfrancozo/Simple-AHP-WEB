@extends ('layouts.index')

@section('menu')

<ul class="navbar-nav mr-auto">

    @guest
    @if (Route::has('login'))
    @endif

    @if (Route::has('register'))
    @endif
    @else

    <li class="nav-item">
        <form method="GET" action="/nodes">
            @csrf
            <button style="border:none;background-color:transparent" type="submit" class="nav-link">My Decision Problems</button>
        </form>
    </li>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <li>
        <form method="POST" action="/formCreateNode/0">
            @csrf
            <button style="border:none;background-color:transparent" type="submit" class="nav-link">New Decision Problem</button>
        </form>
    </li>
    @endguest

</ul>
@stop

@section ('conteudo')
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link " href="report">My results</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="groupreport">Group results</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="HumanReport">Numerical results</a>
    </li>

</ul>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Criteria</h5>
        <table class="table">
            <thead class="thead-light">
                <tr align="center">
                    <th>Criteria</th>
                    @foreach($results->getCriteria() as $q)

                    <th scope="col">
                        <h6 style="color: red"><b>{{$q["descr"]}}</b></h6>
                    </th>

                    @endforeach
                    <th scope="col">
                        <h6 style="color: black"><b>Priority Vector</b></h6>
                    </th>

                </tr>
            </thead>
            <tbody>
                <!--{{$i=-1}}
            foreach ($j_criteria as $c) {
            foreach ($c as $score) {
                printf("%.2f&nbsp;&nbsp;&nbsp;&nbsp;", $score);
            }
            echo "<br>";
        }
-->


                @foreach($j_criteria as $c)

                <tr align="center">
                    <td scope="col">
                        <h6 style="color: blue"><b>{{$results->getCriteria()[++$i]["descr"]}}</b></h6>
                    </td>
                    <!-- {{$j = -1}} -->
                    @foreach($c as $jc)
                    @if($jc < 1) <td scope="col"><a data-toggle="modal" data-target="#excluir_{{$i}}_{{$j+1}}" href="" style="color: red" title="{{$results->getCriteria()[++$j]['descr']}} is {{round(pow($jc,-1),0)}}x most important/relevant than {{$results->getCriteria()[$i]['descr']}}"><b>1/{{round(pow($jc,-1),0)}}</b></a></td>
                        @elseif($jc > 1)
                        <td scope="col">
                            <a data-toggle="modal" data-target="#excluir_{{$i}}_{{$j+1}}" href="" style="color: blue" title="{{$results->getCriteria()[$i]['descr']}} is {{round($jc,3)}}x most important/relevant than {{$results->getCriteria()[++$j]['descr']}}"><b>{{round($jc,3)}}</b></a>
                        </td>
                        @else
                        <td scope="col">
                            <a href="" style="color: black" title="{{$results->getCriteria()[++$j]['descr']}} is indifferent than {{$results->getCriteria()[$i]['descr']}}"><b>{{round($jc,3)}}</b></a>
                        </td>
                        @endif
                        <div class="modal" id="excluir_{{$i}}_{{$j}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Update Judment #{{$i}} _{{$j}}</h4>
                                        <!--<button type="button" class="close" data-dismiss="modal"></button>-->
                                    </div>
                                    <div class="modal-body">
                                        Update comparison between:
                                        <strong>{{$results->getCriteria()[$i]['descr']}} X {{$results->getCriteria()[$j]['descr']}}</strong>
                                    </div>
                                    <form method="POST" action="/UpdateSingleScore">
                                        @csrf
                                        <!--
                                        <select id="newjudment" name="newjudment">
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{9}}">1/9 {{$results->getCriteria()[$j]['descr']}} is 9x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{8}}">1/8 {{$results->getCriteria()[$j]['descr']}} is 8x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{7}}">1/7 {{$results->getCriteria()[$j]['descr']}} is 7x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{6}}">1/6 {{$results->getCriteria()[$j]['descr']}} is 6x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{5}}">1/5 {{$results->getCriteria()[$j]['descr']}} is 5x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{4}}">1/4 {{$results->getCriteria()[$j]['descr']}} is 4x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{3}}">1/3 {{$results->getCriteria()[$j]['descr']}} is 3x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{2}}">1/2 {{$results->getCriteria()[$j]['descr']}} is 2x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1}}">1 {{$results->getCriteria()[$i]['descr']}} is indifferent than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/2}}">2 {{$results->getCriteria()[$i]['descr']}} is 2x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/3}}">3 {{$results->getCriteria()[$i]['descr']}} is 3x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/4}}">4 {{$results->getCriteria()[$i]['descr']}} is 4x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/5}}">5 {{$results->getCriteria()[$i]['descr']}} is 5x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/6}}">6 {{$results->getCriteria()[$i]['descr']}} is 6x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/7}}">7 {{$results->getCriteria()[$i]['descr']}} is 7x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/8}}">8 {{$results->getCriteria()[$i]['descr']}} is 8x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                            <option value="{{$results->getObjectiveId()}};{{$results->getObjectiveId()}};{{$results->getNodeId()[$i]['id']}};{{$results->getNodeId()[$j]['id']}};{{1/9}}">9 {{$results->getCriteria()[$i]['descr']}} is 9x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                        </select>-->
                                        <div class="modal-footer">
                                            <div class="btn-group">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                <!--<a class="btn btn-danger" href="#">Remove</a>-->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <td scope="col">
                            <h6 style="color: black"><b>{{round($results->getPriority()[$i],3)}}</b></h6>
                        </td>
                </tr>@endforeach
            </tbody>
        </table>
        <div align="center"><?php
                            $j_criteria = App\Http\Controllers\AHPController::GetCriteriaJudmentsMatrix($results->getObjectiveId(), 0, null);
                            $consistency_rate = App\Http\Controllers\AHPController::CheckConsistency($j_criteria);
                            $ci = App\Http\Controllers\AHPController::GetConsistencyIndex($j_criteria);
                            $lambda = App\Http\Controllers\AHPController::GetLambdaMax($j_criteria);
                            ?><i>
                &lambda; max: {{round($lambda,3)}}, Consistency Index: {{ round($ci,3)}}, Consistency Rate: {{ round( ($consistency_rate), 3 ) }} ({{ round( ($consistency_rate)*100 , 3 ) }}%)
            </i>
        </div>

    </div>
</div>
<br><br>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Alternatives</h5>
    </div>
</div>
<table class="table">
    <tbody>
        <!-- {{$i=-1}}
    {{$j=-1}} -->
        @foreach($j_alternatives as $a)
        <tr align="center">
            <th>
                <h6 id="{{$results->getCriteria()[$j+1]['id']}}">
                    {{$results->getCriteria()[++$j]['descr']}}
                    <?php
                    $c_alternatives = App\Http\Controllers\AHPController::GetCriteriaJudmentsMatrix($results->getCriteria()[$j]['id'], 0, null);
                    $a_priority = App\Http\Controllers\AHPController::GetPriority($c_alternatives);
                    ?>
                </h6>
            </th>
            @foreach($results->getAlternatives() as $x)
            <th>
                <h6 style="color: red"><b>{{$x["descr"]}}</b></h6>
            </th>
            @endforeach
            <th>
                <h6 style="color: black"><b>Priority Vector</b></h6>
            </th>
        </tr>
        @foreach($a as $b)
        <tr align="center">
            <td>
                <h6 style="color: blue"><b>{{$results->getAlternatives()[++$i]["descr"]}}</b></h6>
            </td>
            <!-- {{$k = -1}} -->
            @foreach($b as $c)
            @if($c < 1) <td>
                <a data-toggle="modal" data-target="#change_{{$j}}_{{$i}}_{{$k+1}}" href="" style="color: red" title="In {{$results->getCriteria()[$j]['descr']}} {{$results->getAlternatives()[++$k]['descr']}} is {{round(pow($c,-1),0)}}x most important/relevant than {{$results->getAlternatives()[$i]['descr']}}">
                    <b>
                        <!--{{$aa = $i}}-->
                        1/{{round(pow($c,-1),0)}}
                    </b>
                </a>
                </td>
                @elseif($c > 1)
                <td>
                    <a data-toggle="modal" data-target="#change_{{$j}}_{{$i}}_{{$k+1}}" href="" style="color: blue" title="In {{$results->getCriteria()[$j]['descr']}} {{$results->getAlternatives()[$i]['descr']}} is {{round($c,2)}}x most important/relevant than {{$results->getAlternatives()[++$k]['descr']}}">
                        <b>
                            {{round($c,2)}}
                        </b>
                    </a>
                </td>
                @else
                <td>
                    <h6 style="color: black">
                        <b>
                            {{round($c,2)}}
                        </b>
                    </h6><!-- {{ ++$k }} -->
                </td>
                @endif

                <!--modal here-->
                <div class="modal" id="change_{{$j}}_{{$i}}_{{$k}}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Update Judment #{{$i}} _{{$j}}</h4>
                                <!--<button type="button" class="close" data-dismiss="modal"></button>-->
                            </div>
                            <div class="modal-body">
                                Update comparison in <i>{{$results->getCriteria()[$j]['descr']}}</i> between:
                                <strong>{{$results->getAlternatives()[$i]['descr']}} X {{$results->getAlternatives()[$k]['descr']}}</strong>
                            </div>
                            <form method="POST" action="/UpdateSingleScore">
                                @csrf
                                <select id="newjudment" name="newjudment">
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{9}}">1/9 {{$results->getCriteria()[$j]['descr']}} is 9x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{8}}">1/8 {{$results->getCriteria()[$j]['descr']}} is 8x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{7}}">1/7 {{$results->getCriteria()[$j]['descr']}} is 7x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{6}}">1/6 {{$results->getCriteria()[$j]['descr']}} is 6x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{5}}">1/5 {{$results->getCriteria()[$j]['descr']}} is 5x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{4}}">1/4 {{$results->getCriteria()[$j]['descr']}} is 4x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{3}}">1/3 {{$results->getCriteria()[$j]['descr']}} is 3x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{2}}">1/2 {{$results->getCriteria()[$j]['descr']}} is 2x preferable than {{$results->getCriteria()[$i]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1}}">1 {{$results->getCriteria()[$i]['descr']}} is indifferent than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/2}}">2 {{$results->getCriteria()[$i]['descr']}} is 2x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/3}}">3 {{$results->getCriteria()[$i]['descr']}} is 3x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/4}}">4 {{$results->getCriteria()[$i]['descr']}} is 4x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/5}}">5 {{$results->getCriteria()[$i]['descr']}} is 5x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/6}}">6 {{$results->getCriteria()[$i]['descr']}} is 6x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/7}}">7 {{$results->getCriteria()[$i]['descr']}} is 7x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/8}}">8 {{$results->getCriteria()[$i]['descr']}} is 8x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                    <option value="{{$results->getObjectiveId()}};{{$results->getCriteria()[$j]['id']}};{{$results->getAlternatives()[$i]['id']}};{{$results->getAlternatives()[$k]['id']}};{{1/9}}">9 {{$results->getCriteria()[$i]['descr']}} is 9x preferable than {{$results->getCriteria()[$j]['descr']}}</option>
                                </select>
                                <div class="modal-footer">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        <!--<a class="btn btn-danger" href="#">Remove</a>-->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- end modal -->



                @endforeach
                <td scope="col">
                    <h6 style="color: black"><b>{{ round($a_priority[$i],3)}}</b></h6>
                </td>
        </tr>

        @endforeach
        <!-- {{$i=-1}} -->
        <tr>
            <td align="center" colspan="{{$k+3}}">
                <?php
                $j_criteria = App\Http\Controllers\AHPController::GetCriteriaJudmentsMatrix($results->getNodeId()[$j]['id'], 0, null);
                $consistency_rate = App\Http\Controllers\AHPController::CheckConsistency($j_criteria);
                $ci = App\Http\Controllers\AHPController::GetConsistencyIndex($j_criteria);
                $lambda = App\Http\Controllers\AHPController::GetLambdaMax($j_criteria);
                ?>
                <i>
                    &lambda; max: {{round($lambda,3)}}, Consistency Index: {{ round($ci,3)}}, Consistency Rate: {{ round( ($consistency_rate), 3 ) }} ({{ round( ($consistency_rate)*100 , 3 ) }}%)
                </i>
                <hr color="black">
            </td>
        </tr>
        @endforeach



    </tbody>
</table>

<hr color="FFOOOO">

@stop
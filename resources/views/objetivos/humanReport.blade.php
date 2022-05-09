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
        <a class="nav-link " href="report">Graphical results</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="NumericalReport">Numerical results</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="HumanReport">Human results</a>
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
                        <h6 style="color: blue"><b>{{$q["descr"]}}</b></h6>
                    </th>

                    @endforeach

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
                        <h6 style="color: red"><b>{{$results->getCriteria()[++$i]["descr"]}}</b></h6>
                    </td>
                    <!-- {{$j = -1}} -->
                    @foreach($c as $jc)
                    @if($jc < 1) <td scope="col"><a href="" style="color: red" title="{{$results->getCriteria()[$i]['descr']}} is {{round(pow($jc,-1),0)}}x most important/relevant than {{$results->getCriteria()[++$j]['descr']}}"><b>{{round(pow($jc,-1),0)}}</b></a></td>
                        @elseif($jc > 1)
                        <td scope="col">
                            <a href="" style="color: blue" title="{{$results->getCriteria()[++$j]['descr']}} is {{round($jc,3)}}x most important/relevant than {{$results->getCriteria()[$i]['descr']}}"><b>{{round($jc,3)}}</b></a>
                        </td>
                        @else
                        <td scope="col">
                            <a href="" style="color: black" title="{{$results->getCriteria()[++$j]['descr']}} is indifferent than {{$results->getCriteria()[$i]['descr']}}"><b>{{round($jc,3)}}</b></a>
                        </td>
                        @endif

                        @endforeach

                </tr>@endforeach
            </tbody>
        </table>


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
            <th>{{$results->getCriteria()[++$j]['descr']}}</th>
            @foreach($results->getAlternatives() as $x)
            <th>
                <h6 style="color: blue"><b>{{$x["descr"]}}</b></h6>
            </th>
            @endforeach
        </tr>
        @foreach($a as $b)
        <tr align="center">
            <td>
                <h6 style="color: red"><b>{{$results->getAlternatives()[++$i]["descr"]}}</b></h6>
            </td>
            <!-- {{$k = -1}} -->
            @foreach($b as $c)
            @if($c < 1) <td>
                <a href="" style="color: red" title="In {{$results->getCriteria()[$j]['descr']}} {{$results->getAlternatives()[$i]['descr']}} is {{round(pow($c,-1),0)}}x most important/relevant than {{$results->getAlternatives()[++$k]['descr']}}">
                    <b>{{ round(pow($c,-1),0)}}</b>
                </a>
                </td>
                @elseif($c > 1)
                <td>
                <a href="" style="color: blue" title="In {{$results->getCriteria()[$j]['descr']}} {{$results->getAlternatives()[++$k]['descr']}} is {{round($c,2)}}x most important/relevant than {{$results->getAlternatives()[$i]['descr']}}">
                    <b>{{round($c,2)}}</b>
    </a>
                </td>
                @else
                <td>
                    <h6 style="color: black"><b>{{round($c,2)}}</b></h6><!-- {{ ++$k }} -->
                </td>
                @endif

                @endforeach
        </tr>
        @endforeach
        <!-- {{$i=-1}} -->

        @endforeach



    </tbody>
</table>

<hr color="FFOOOO">

@stop
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
  
</ul>
<div class="card">
<div class="card-body">
 <h5 class="card-title">Criteria</h5>
 <table class="table">
    <thead class="thead-light"> 
    <tr align="center">
        <th>Critérios</th>
        @foreach($results->getCriteria() as $q)
        
            <th scope="col">{{$q["descr"]}}</th>
        
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
    <td scope="col">{{$results->getCriteria()[++$i]["descr"]}}</td>
        @foreach($c as $jc)
                    
            <td scope="col">{{round($jc,3)}}</td>
                    
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
        <th>{{$x["descr"]}}</th>
        @endforeach
        </tr>
        @foreach($a as $b)
        <tr align="center">
        <td>{{$results->getAlternatives()[++$i]["descr"]}}</td>
            @foreach($b as $c)
            <td>{{round($c,3)}}</td>
            @endforeach
            </tr>
        @endforeach <!-- {{$i=-1}} -->
        
        @endforeach
    

    
    </tbody>
 </table>

 <hr color="FFOOOO">

 @stop
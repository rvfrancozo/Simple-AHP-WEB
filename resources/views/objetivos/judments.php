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

@section('conteudo')
  <h3>AHP - Tomada de Decisões</h3>
  <p>AHP (Analytic Hierarchy Process)</p>
  <p> Um dos métodos multicritério mais utilizados, Criado pelo Professor Thomas L. Saaty em 1980;</p>
  <p> Permite o uso de critérios qualitativos bem como quantitativos no processo de avaliação. </p> 
  <p>A ideia principal é dividir o problema de decisão em níveis hierárquicos, facilitando, assim, sua compreensão e avaliação.</p>
@stop
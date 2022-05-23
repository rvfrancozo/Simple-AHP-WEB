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
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/social.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/social-icons.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">

<h3><i>Simple</i> - AHP</h3>
<p>Este projeto visa disponibilizar uma ferramenta web para tomada de decisão no escopo do AHP.</p>
<p>Ao contrário do AHP clássico que demanda (n² - n)/2 comparações em cada nível da hierarquia, nossa proposta demanda n-1 comparações onde n é a quantidade de vértices (critérios, sub-critérios ou alternativas) a serem comparados.</p>
<p>Esta versão é baseada na linearização dos julgamentos no AHP ao fixar a inconsistência do decisor como 0. A literatura apresenta alguns estudos semelhantes notadamente em:</p>
<br>

<ul>
<li><a href="https://doi.org/10.1016/j.mex.2019.11.021" target="_blank">LEAL, José Eugenio. AHP-express: A simplified version of the analytical hierarchy process method. <b>MethodsX</b>, v. 7, p. 100748, 2020.</a></li>
<li><a href="https://doi.org/10.1155/2019/2125740" target="_blank">VASCONCELOS, Giancarllo Ribeiro; MOTA, Caroline Maria de Miranda. Exploring multicriteria elicitation model based on pairwise comparisons: Building an interactive preference adjustment algorithm. <b>Mathematical Problems in Engineering</b>, v. 2019, 2019.</a></li>
<li><a href="http://din.uem.br/sbpo/sbpo2014/pdf/arq0173.pdf" target="_blank">VASCONCELOS, Giancarllo Ribeiro; MOTA, Caroline Maria de Miranda. Modelo multicritério de comparação par a par baseado no AHP: proposta de linearização do processo de comparação. <b>XLVI Simpósio Brasileiro de Pesquisa Operacional</b>, 2014.</a></li>
</ul>

<p>Adicionalmente o decisor tem a oportunidade de validar a sua decisão e inclusive conscientemente realizar um julgamento inconsistente.</p>
<p>A versão atual encontra-se em fase de desenvolvimento portanto alguns bugs ainda podem ocorrer. </p>

<p>Em versões futuras pretendemos disponibilizar os seguintes recursos:</p>

<ul>
<li>Tomada de decisão em grupo aij e aip com definição dos pesos para os decisores.</li>
<li>Análise de sensibilidade para validar a robustez da decisão.</li>
<li>Adição de mais níveis na hieraquia como sub-critérios.</li>
<li>Uso do AHP com ratings.</li>
<li>Tomada de decisão no escopo do ANP.</li>
</ul>

<p>Caso tenha dúvidas ou se desejar contribuir com o projeto, entre em contato com rvfrancozo@gmail.com</p>

<hr>

<p><i>History</i></p>

<b>2022-05-23:</b> <i>revision of the alternatives judgments released</i>
<p>Now, in Human report, the decision maker can adjust the alternatives judgments as he wishes.</p>

<b>2022-05-20:</b> <i>Beta version released</i>
<p>The first full functional version is released. In current version the user can login with his or her google or github account.</p>

<p>The user can also elaborate a decision problem with criteria and alternatives in the scope of the AHP. There are basically two ways to make the judgments: the first way is through the comparisons right after registering the criteria or alternatives, the second way is directly in the judgment matrix through the human report panel.</p>

<hr><b>2022-04-20:</b> <i>Alpha version released</i><hr>

@stop
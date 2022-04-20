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
<!-- {{ $x = 0 }}  -->
<div>



	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Decision Problems</th>
				<th>View</th>
				<th>Operations</th>
			</tr>
		</thead>
		<tbody>
			@foreach($objectives as $o)

			<tr>
				<td>{{ ++$x }}</td>
				<td>
					{{ $o['descr'] }}
				</td>
				<td>
					<div class="btn-group">
						<a href="/nodes/{{ $o['id'] }}/criteria" class="btn btn-sm btn-primary" data-toggle="tooltip" title="">Criteria</a>
						<a href="/nodes/{{ $o['id'] }}/alternatives" class="btn btn-sm btn-primary" data-toggle="tooltip" title="teste">Alternatives</a>
					</div>
				</td>

				<td>
					<div class="btn-group">
						<a class="btn btn-primary btn-sm" href="/nodes/{{$o->id}}/report">Report</a>
						<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#excluir_{{$o->id}}">Remove</button>
					</div>
					<div class="modal" id="excluir_{{$o->id}}">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Are you sure? #{{$o->id}}</h4>
									<button type="button" class="close" data-dismiss="modal"></button>
								</div>
								<div class="modal-body">
									<strong>Decision Problem:</strong> {{$o->descr}}
								</div>
								<div class="modal-footer">
									<div class="btn-group">
										<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
										<a class="btn btn-danger" href="/node/{{$o->id}}/remove">Remove</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>


			@endforeach

		</tbody>
	</table>




	@stop
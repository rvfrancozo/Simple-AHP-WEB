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
<div class="card">
  <div class="card-header">
    <h3>Create new {{$descr}}</h3>
  </div>
  <div class="card-body">
    <form autocomplete="off" method="POST" action="/createNode/{{$up}}">
        @csrf
        <div class="form-group">
        @for ( $i = 0; $i < $nodes; $i++ )
        <label for="descricao">Name:</label>
        <input autocomplete="off" type="text" class="form-control" placeholder="" id="descricao" name="descricao[{{$i}}]">
        @endfor
        <input autocomplete="off" type=hidden name="level" value={{$level}}>
      </div>
      <div class="btn-group">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-danger" href="/nodes">Cancel</a>
      </div>
    </form>
  </div>
</div>
@stop


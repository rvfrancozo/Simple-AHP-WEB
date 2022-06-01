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
    <h3>Alternatives to Decision Problem: {{ $goal->descr }}</h3>
  </div>
  <div class="card-body">
    <table class="table">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>Descrição</th>
          <th>Operações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($alternatives as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td>{{ $c->descr }}</td>
          <td>
            <div class="btn-group">
          <a class="btn btn-sm btn-primary" href="\comparisons\{{$goal->id}}\{{$c->id}}">Comparisons</a>
          <button type="button" disabled class="btn btn-sm btn-danger" data-toggle="modal" data-target="#excluir_{{$c->id}}">Remove</button>

              <!-- Modal aqui -->
              <!-- The Modal -->
              <div class="modal" id="excluir_{{$c->id}}">
                <div class="modal-dialog">
                  <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Deseja excluir o Criterio #{{$c->id}}?</h4>
                    <button type="button" class="close" data-dismiss="modal"></button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <strong>Descrição:</strong> {{$c->descr}}
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger" href="/criterio/{{$c->id}}/excluir">Excluir</a>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table><hr>

    <div class="btn-group">
      @if(count($alternatives) == 0)
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rexcluir_{{$goal->id}}">New Alternative(s)</button>
@endif
              <!-- Modal aqui -->
              <!-- The Modal -->
              <div class="modal" id="rexcluir_{{$goal->id}}">
                <div class="modal-dialog">
                  <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">How many alternatives would you like to add? (at least two)</h4>
                    <button type="button" class="close" data-dismiss="modal"></button>
                  </div>

                  <!-- Modal body -->
                  <div class="modal-body">
                    <form method="POST" action="/formCreateNode/{{$goal->id}}">
                        @csrf
                      <div class="form-group">
                        <label for="descricao">Name:</label>
                        <select class="custom-select" name="nodes">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="10">11</option>
                            <option value="10">12</option>
                            <option value="10">13</option>
                            <option value="10">14</option>
                            <option value="10">15</option>
                        </select>
                      </div>
                      <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Go</button>
                        <a class="btn btn-danger" href="/nodes">Cancel</a>
                      </div>
                      <input type="hidden" name="type" value="2">
                    </form>
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                    <div class="btn-group">
                    </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>

  </div>
</div>
@stop
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
        <h5>Add new decision-maker to Decision Problem: {{$descr}}</h5>
    </div>
    <div class="card-body">
        <form autocomplete="off" method="POST" action="/createDM/{{$id}}">
            @csrf
            <div class="form-group">
                <label for="descricao">E-mail of new decision-maker:</label>
                <input autocomplete="off" type="text" class="form-control" placeholder="" id="descricao" name="descricao">
                <input autocomplete="off" type=hidden name="level" value="">
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-danger" href="/nodes">Cancel</a>
            </div>
        </form>
    </div>
</div>
<hr>
<div class="card">
    <div class="card-header">
        <h5>List of decision-maker for Decision Problem: {{$descr}}</h5>
    </div>
    <?php
    $dmsb = App\Models\GroupDecision::where('node', $id)->get();
    ?>
    <div class="card-body">
        @foreach($dmsb as $dm)
        {{$dm['email']}}
        <hr>
        @endforeach
    </div>
</div>
@stop
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

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th style="width:5%"></th>
                        <th style="width:75%">Decision-maker</th>
                        <th>Weight</th>
                        <th colspan="2" align="center">Actions</th>
                    </tr>
                </thead>
                <tr>
                    <td>
                        @if(Auth::user()->avatar == "none")
                        <img class="rounded-circle" width="25" height="25" src="{{ asset('images/ahp.jpg') }}">
                        @else
                        <img class="rounded-circle" width="25" height="25" src="{{ Auth::user()->avatar }}">
                        @endif
                    </td>
                    <td>{{Auth::user()->email}}</td>
                    <td align="center">1</td>
                    <td><img width="25" height="25" src="{{ asset('images/decision.png') }}"></td>
                    <td></td>
                </tr>

                @foreach($dms as $dm)
                <tr>
                    <td>
                        @if($dm['avatar'] == "none")
                        <img class="rounded-circle" width="25" height="25" src="{{ asset('images/ahp.jpg') }}">
                        @else
                        <img class="rounded-circle" width="25" height="25" src="{{ $dm['avatar'] }}"> 
                        @endif
                    </td>
                    <td>{{$dm['email']}}</td>
                    <td align="center">
                        {{round($dm['weight'],2)}}
                    </td>
                    <td>
                        <a href="#" value="Compare the relevance of this decision-maker with the others for this decision problem"><img width="25" height="25" src="{{ asset('images/decision.png') }}"></a>
                    </td>
                    <td>
                        <a href="#" value="Delete this decision-maker and all his judments for this decision problem"><img width="25" height="25" src="{{ asset('images/remove.png') }}"></a>
                    </td>
                </tr>
                @endforeach

            </table>
        </div>
    </div>


</div>
@stop
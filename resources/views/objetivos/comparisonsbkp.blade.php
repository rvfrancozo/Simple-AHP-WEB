
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
<?php
$i = 0;
$score = array(1 / 9, 1 / 8, 1 / 7, 1 / 6, 1 / 5, 1 / 4, 1 / 3, 1 / 2);
?>
<form method="POST" action="/UpdateScore/{{$id}}">
    @csrf
    @foreach($goal as $g)
    <hr><b>In respect to <i>{{ $g->descr }}</i></b>
    <hr>

    <table class="table">
        <tbody>
            @foreach($itens as $c)
            <tr>
                <td>{{$target[0]->descr}} x {{ $c->descr }}</td>
                <td>
                    <select name="score{{$i}}" class="custom-select">
                        @if ($scores[$i] < 1) 
                        <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$scores[$i]}}" selected>Current judment: {{ $target[0]->descr }} is {{round(pow($scores[$i],(-1)),3)}}x preferable to {{$c->descr}} </option>
                        @elseif ($scores[$i] > 1) 
                        <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$scores[$i]}}" selected>Current judment: {{ $c->descr }} is {{round(pow($scores[$i],(1)),3)}}x preferable to {{ $target[0]->descr }} </option>
                        @else
                        <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$scores[$i]}}" selected>Current judment: {{ $target[0]->descr }} is indifferent to {{$c->descr}} </option>
                        @endif
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[0]}}">1/9 - {{ $c->descr }} is very strongly preferable to {{$target[0]->descr}}</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[1]}}">1/8 - Intermediate judment</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[2]}}">1/7 - {{ $c->descr }} is very strongly preferable to {{$target[0]->descr}}</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[3]}}">1/6 - Intermediate judment </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[4]}}">1/5 - {{ $c->descr }} is strongly preferable to {{$target[0]->descr}}</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[5]}}">1/4 - Intermediate judment </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[6]}}">1/3 - {{ $c->descr }} is moderately preferable to {{$target[0]->descr}}</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};{{$score[7]}}">1/2 - Intermediate judment </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};1">1 - {{$target[0]->descr}} is indifferent to {{ $c->descr }}</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};2">2 - Intermediate judment </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};3">3 - {{$target[0]->descr}} is moderately preferable to {{ $c->descr }} </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};4">4 - Intermediate judment</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};5">5 - {{$target[0]->descr}} is strongly preferable to {{ $c->descr }} </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};6">6 - Intermediate judment</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};7">7 - {{$target[0]->descr}} is very strongly preferable to {{ $c->descr }} </option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};8">8 - Intermediate judment</option>
                            <option value="{{$g->id}};{{$target[0]->id}};{{$c->id}};9">9 - {{$target[0]->descr}} is extremely preferable to {{ $c->descr }} </option>
                    </select>
                </td>
            </tr>
            <?php $i++; ?>
            @endforeach
        </tbody>
    </table>

    @endforeach
    <hr>
    <div class="btn-group">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-danger" href="/nodes">Cancel</a>
    </div>
    <input type="hidden" name="counter" value="{{$i}}">
</form>
@stop
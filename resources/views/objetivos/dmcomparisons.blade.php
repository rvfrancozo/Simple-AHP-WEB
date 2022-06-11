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
<form method="POST" action="/dmweights/{{$goal->id}}">
    @csrf
    <hr><b>For <i>{{$goal->descr}}</i> decision problem...</b>

    <table class="table">
        <tbody>
            <tr>
                <td>{{substr($proxy->email,0,strpos($proxy->email, '@') )}} x {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</td>
                <td>
                    <select name="score{{$i++}}" class="custom-select">
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/9}}">9 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 9x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/8}}">1/8 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 8x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/7}}">1/7 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 7x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/6}}">1/6 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 6x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/5}}">1/5 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 5x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/4}}">1/4 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 4x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/3}}">1/3 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 3x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;{{1/2}}">1/2 - {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}} is 2x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option selected value="{{$goal->id}};{{$proxy->id}};{{Auth::user()->id}};1">1 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is equal to {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;2">2 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 2x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;3">3 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 3x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;4">4 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 4x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;5">5 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 5x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;6">6 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 6x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;7">7 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 7x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;8">8 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 8x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};0;9">9 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 9x most important than {{substr(Auth::user()->email,0,strpos(Auth::user()->email, '@') )}}</option>
                    </select>
                </td>
            </tr>
            @foreach($dms as $dm)
            <tr>
                <td>{{substr($proxy->email,0,strpos($proxy->email, '@') )}} x {{substr($dm->email,0,strpos($dm->email, '@') )}}</td>
                <td>
                    <select name="score{{$i}}" class="custom-select">
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/9}}">1/9 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 9x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/8}}">1/8 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 8x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/7}}">1/7 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 7x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/6}}">1/6 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 6x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/5}}">1/5 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 5x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/4}}">1/4 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 4x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/3}}">1/3 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 3x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};{{1/2}}">1/2 - {{substr($dm->email,0,strpos($dm->email, '@') )}} is 2x most important than {{substr($proxy->email,0,strpos($proxy->email, '@') )}}</option>
                        <option selected value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};1">1 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is equal to {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};2">2 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 2x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};3">3 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 3x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};4">4 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 4x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};5">5 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 5x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};6">6 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 6x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};7">7 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 7x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};8">8 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 8x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                        <option value="{{$goal->id}};{{$proxy->id}};{{$dm->id}};9">9 - {{substr($proxy->email,0,strpos($proxy->email, '@') )}} is 9x most important than {{substr($dm->email,0,strpos($dm->email, '@') )}}</option>
                    </select>
                </td>
            </tr>
            <?php $i++; ?>
            @endforeach
        </tbody>
    </table>
    <div class="btn-group">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-danger" href="/nodes">Cancel</a>
    </div>
    <input type="hidden" name="counter" value="{{$i}}">
</form>
@stop
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Decision Problems   ') }}</div>
                <!-- {{ $x = 0 }}  -->
                <div>
                    <!--
                    <form method="POST" action="/formCreateNode/0">
                        @csrf
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">New Decision Problem</button>
                        </div>
                    </form>
-->
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Decision Problem</th>
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




                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <!-- __('You are logged in!') $user->email -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    <!-- $user->email -->
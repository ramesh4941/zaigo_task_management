@extends('layouts.app')

@section('content')
<style>
    .hover-color a:hover{
        background-color: gold;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Task</div>
                <div class="card-body">
                    @if (session('saved'))
                        <div class="alert alert-success" role="alert">
                            {{ session('saved') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="co-md-12">
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Create Task</h5><hr>
                                    <form class="form-inline" role="form" action="{{route('create_task')}}" method="POST" enctype="multipart/form-data">
                                        @csrf()
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <label for="title">Task Title</label>
                                                <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" placeholder="Task Title" value="{{ old('title') }}" required id="title">
                                                
                                                @error('title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Description" required id="description" name="description">{{ old('description') }}</textarea>
                                                
                                                @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-8">
                                                <input type="file" class="form-control" name="task_attachment">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-dark" style="width: 100%">Create Task</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @foreach ($tasks as $task)
                            <div class="co-md-12 hover-color">
                                <a href="{{route('task_details',$task->id)}}" style="text-decoration: none !important;">
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">{{$task->title}}</h5>
                                            <p class="card-text mb-0" style="text-align: justify">
                                                @if(strlen($task->description)<= 200)
                                                    {{$task->description}}
                                                @else
                                                    {{substr($task->description,0,200) . ' ... Read more'}}
                                                @endif
                                            </p>
                                            <small class="card-text">
                                                {{date("d M, Y h:i A", strtotime($task->created_at))}} | 
                                                <span class="badge bg-dark">
                                                    @if($task->assigned_user == Auth::id())
                                                        Assigned by: {{$task->created_user->name}}
                                                    @else
                                                        @if($task->assigned_user == null)
                                                            Not Assigned
                                                        @else
                                                            Assigned to: {{$task->assigned_users->name}}
                                                        @endif
                                                    @endif 
                                                </span> |
                                                @if($task->status == 1)
                                                    <span class="badge bg-info">In progress</span>
                                                @elseif($task->status == 2)
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">Open</span>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.authenticated')

@section('content')

<div class="container">
    <div class="container">
        <div class="row justify-content-center align-content-center mt-5">
            <div class="col-md-5">
                <form action="{{ route('usrinfo.store') }}" method="post" class="card p-4 shadow">
                    <a href="{{ route('userinfo.index')}}" class="ms-auto text-decoration-none text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-x" viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </a>
                    <h5 class="text-center">Add New User</h5>
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span>{{ $message }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    @csrf
                    <div class="form-group my-1">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="John" value="{{old('firstname')}}">
                        @error('firstname')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="middle_initial">Middle Initial</label>
                        <input type="text" name="middle_initial" id="middle_initial" class="form-control" placeholder="D." value="{{old('middle_initial')}}">
                        @error('middle_initial')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Doe" value="{{old('lastname')}}">
                        @error('lastname')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="xxx@elink.com.ph" value="{{old('email')}}">
                        @error('email')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password" class="form-control"  value="{{$print}}">
                        @error('email')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control ">
                            <option value="all" selected>Select Department</option>
                            @foreach ($departments as $dept)
                             
                            <option value="{{ $dept->deptcode }}">{{$dept->deptname }}</option>
                            @endforeach
                        </select>
                        @error('useraccess')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group my-1">
                    <label for="useraccess">Select User Access</label>
                    <select name="useraccess" id="useraccess" class="form-control ">
                            <option value="all" selected>Select a Access type</option>
                            @foreach ($usertype as $ut)
                             
                            <option value="{{ $ut->id }}">{{$ut->usertype }}</option>
                            @endforeach
                        </select>
                        @error('useraccess')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                       
                    </div>
                    <div class="form-group my-1">
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.authenticated')

@section('content')
    <div class="container ">
        <div class="p-3 my-3 w-100 ">
            <div class="d-flex">
            <form action="{{route('userinfo.filterdept')}}" method="get" class="d-flex gap-2">
                <div class="form-group my-2">
                    <select name="deptcode" id="deptcode" class="form-control select2 w-75">
                        <option value="all" selected>Show all dept</option>
                        @foreach ($department as $dept)
                            @if (request()->get('deptcode') == $dept->deptcode)
                                <option value="{{$dept->deptcode}}" selected>{{$dept->deptname}}</option>
                            @else
                                <option value="{{$dept->deptcode}}">{{$dept->deptname}}</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
            OR
            <form action="{{route('userinfo.getuser')}}" method="get" class="d-flex gap-2">
                <div class="form-group my-2">
                <select name="user" id="user" class="form-control-lg select2">
                        <option value="all" selected>Show all user</option>
                        @foreach ($searchuser as $getuser)
                            @if (request()->get('user') == $getuser->id)
                                <option value="{{$getuser->id}}" selected>{{($getuser->getFullName())}}</option>
                            @else
                                <option value="{{$getuser->id}}">{{($getuser->getFullName())}}</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
            
              
                   
                </div>
                <div class="ms-auto">
                <a href="{{route('usrinfo.register')}}" class="btn btn-outline-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                      </svg>
                    Add User
                </a>
                {{-- <a href="{{ route('usrinfo.import-page') }}" class="btn btn-outline-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-download" >
                            <path
                                d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                            <path
                                d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                        </svg>
                        Bulk Import
                    </a>--}}
               
                    @if( auth()->user()->usertype() == 1 &&   auth()->user()->usertype() == 2 )
                    <a href="{{ route('pod.clear') }}"
                onclick="return confirm('Are you sure you want to Clear ALL?')"
                 class="btn btn-danger" > Clear All</a>
                 @endif
                    {{-- <a href="{{route('pod.create')}}" class="btn btn-outline-success ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                      </svg>
                    Add Transaction
                </a> --}}
            </div>
            </div>
           
            <div class="bg-light p-2 shadow rounded table-responsive">
                <h5 class="text-center my-3">User Management</h5>
                <table class="table table-bordered table-hover mt-2">
                    <thead>
                        <tr class="text-center">
                            
                            <th>Firstname</th>
                            <th>lastname</th>
                            <th>MI</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>usertype</th>
                            <th>Status</th>
                           
                         
                            @if( auth()->user()->usertype() == 1  ||  auth()->user()->usertype() == 2 )
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                      
                            <tr>
                                <td>{{ $user->firstname }}</td>
                                <td>{{  $user->lastname }}</td>
                                <td>{{  $user->middlename }}</td>
                                <td>{{  $user->email }}</td>
                                <td>{{  $user->department}}</td>
                                <td>{{ App\Helpers\UsertypeHelper::getStringtype($user->usertype) }}</td>
                               <td>soon</td>
                                @if( auth()->user()->usertype() == 1 )
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href=" {{route('usrinfo.edit', ['users' => $user])}}"
                                            class="btn btn-outline-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('usrinfo.delete', ['users' => $user]) }}"
                                            onclick="return confirm('Are you sure you want to delete this file?')"
                                            class="btn btn-outline-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                <path fill-rule="evenodd"
                                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                            </svg>
                                        </a>
                                       
                                     
                                    </div>
                               
                                </td>
                                @endif 
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No record found</td>
                            </tr>
         
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection


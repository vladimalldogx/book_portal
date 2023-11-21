@extends('layouts.authenticated')

@section('content')
<div class="container">
    <div class="row justify-content-center align-content-center mt-5">
        <div class="col-md-5">
            <form action="{{ route('author.update',['author' => $author]) }}" method="post" class="card p-4 shadow">
                <a href="{{ route('author.index')}}" class="ms-auto text-decoration-none text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-x" viewBox="0 0 16 16">
                        <path
                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                </a>
                <h5 class="text-center">Update Author</h5>
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span>{{ $message }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @csrf
                @method('PUT')
                <div class="form-group my-1">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Dr." value="{{old('title') ?? $author->title}}">
                    @error('title')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" id="firstname" class="form-control" placeholder="John" value="{{old('firstname') ?? $author->firstname}}">
                    @error('firstname')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="middle_initial">Middle Initial</label>
                    <input type="text" name="middle_initial" id="middle_initial" class="form-control" placeholder="D." value="{{old('middle_initial') ?? $author->middle_initial}}">
                    @error('middle_initial')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Doe" value="{{old('lastname') ?? $author->lastname}}">
                    @error('lastname')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="suffix">Suffix</label>
                    <input type="text" name="suffix" id="suffix" class="form-control" placeholder="Sr." value="{{old('suffix') ?? $author->suffix}}">
                    @error('suffix')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="xxx@elink.com.ph" value="{{old('email') ?? $author->email}}">
                    @error('email')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="09xxxxxxxx" value="{{old('contact_number') ?? $author->contact_number}}">
                    @error('contact_number')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group my-1">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control" cols="10" rows="3">{{old('address') ?? $author->address}}</textarea>
                    @error('address')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <!--superadmin and admin access only-->
                @if(auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2)
                <div class="form-group my-1">
                        <label for="pubcon">Assign Pubcon </label>
                        <select name="pubcon" class="select2 form-control" id="pubcon">
                           
                        @if(!empty($author->user_id ))
                        <option value="{{old('pubcon}') ?? $author->user_id}}"  selected>{{$author->user->getFullName()}} Current</option>
                        @endif
                        <option  >Assign Pubcon</option>
                            @foreach ($getuser as $pubcon)
                                <option value="{{ $pubcon->id }}">{{ $pubcon->getFullName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group my-1">
                   <label for="pubcon">Assign Aro </label>
                   <select name="aro" class="select2 form-control" id="aro">
                    @if(!empty($author->aro_user_id ))
                   <option value="{{old('aro') ?? $author->aro_user_id}}"  >{{ $author->user2->getFullName()}} Current</option>
                    @elseif(empty(($author->aro_user_id )))
                   
                       <option value ="" selected >Assign Aro</option>
                       @foreach ($getaro as $aro)
                           <option value="{{ $aro->id }}">{{ $aro->getFullName() }}</option>
                       @endforeach
                       @endif
                   </select>
               </div>
                    @endif
                    <!--end admin assignee-->
                       <!--for sales and aro manager only-->
                    @if(auth()->user()->usertype() == 3 && auth()->user()->dept() == 'SALES')
                    <div class="form-group my-1">
                        <label for="pubcon">Assign Pubcon </label>
                        <select name="pubcon" class="select2 form-control" id="pubcon">
                        @if(!empty($author->user_id ))
                        <option value="{{old('pubcon}') ?? $author->user_id}}"  selected>{{$author->user->getFullName()}} Current</option>
                        @endif
                        
                            <option >Assign Pubcon</option>
                            @foreach ($getuser as $pubcon)
                                <option value="{{ $pubcon->id }}">{{ $pubcon->getFullName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    @if(auth()->user()->usertype() == 3 && auth()->user()->dept() == 'ARO')
                    <div class="form-group my-1">
                   <label for="pubcon">Assign Aro </label>
                   <select name="aro" class="select2 form-control" id="aro">
                   @if(!empty($author->aro_user_id ))
                   <option value="{{$author->aro_user_id}}"  >{{$author->user2->getFullName()}} Current </option>
                  @endif
                 
                       <option value disabled  >Assign Aro</option>
                       @foreach ($getuser as $aro)
                           <option value="{{ $aro->id }}">{{ $aro->getFullName() }}</option>
                       @endforeach
                  
                   </select>    
               </div>
                    @endif
                    
                    <!--for sales and aro manager end access here-->
                <div class="form-group my-1">
                    <label for="specroyal"> Special Royalty(if he/she had)</label>
                    <input name="specroyal" id="specroyal" class="form-control" value ="{{old('specroyal') ?? $author->specroyal}}" type="text">
                  
                </div>
                <div class="form-group my-1">
                    <button type="submit" class="btn btn-primary">Update Author</button>
                </div>
            </form>
        </div>
    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#year").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
            $('.select2').select2();
        });
    </script>
@endsection

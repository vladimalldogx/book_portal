<nav class="navbar navbar-expand-lg bg-light shadow">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/Slava Ukraine.jpg') }}" height="50" width="50" alt="" srcset="">
            <span class="mt-2">Test Only</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }} " aria-current="page"
                        href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('authors') ? 'active' : '' }} "
                        href="{{ route('author.index') }}">Authors</a>
                </li>
                <li class="nav-item ">
                    <a class=" d-flex gap-2 nav-link {{ request()->is('books') ? 'active' : '' }} "
                        href="{{ route('book.index') }}">Books

                    </a>
                </li>
                <!----Royalty--->
                @if(auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2 || auth()->user()->usertype() == 3 )
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Royalty
                    </a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" disabled>POD (Maintenance mode)</a></li>
                      {{--<li><a class="dropdown-item" href="{{ route('royalty.index') }}">POD</a></li>--}}  
                        <li><a class="dropdown-item" href="{{ route('er.index') }}">Ebook</a></li>
                    </ul>
                </li>
                @endif
                <!----end royalty--->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex gap-2" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Transactions
                      
                    </a>
                  
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('pod.index') }}">POD
                     
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('ebook.index') }}">Ebook
                       
                        </a></li>
                    </ul>
                  
                </li>
                @if(auth()->user()->usertype() == 1 || auth()->user()->usertype() == 2 || auth()->user()->usertype() == 3 )
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex gap-2" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Fail Transactions
                        <x-rejected-transaction></x-rejected-transaction>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item d-flex gap-2" href="{{ route('rejecteds-pods.index') }}">POD
                                <x-rejected-pod>
                                </x-rejected-pod>
                            </a></li>
                        <li><a class="dropdown-item d-flex gap-2" href="{{ route('rejecteds-ebooks.index') }}">Ebook
                                <x-rejected-ebook>
                                </x-rejected-ebook>
                            </a></li>
                    </ul>
                </li>
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Hello!
                        {{ auth()->user()->lastName() }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('user.profile') }}"> {{ auth()->user()->getFullName() }}</a> </li>
                       
                        <li><a class="dropdown-item" href="{{ route('user.edit-password') }}">Change Password</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        @if( auth()->user()->usertype() == 1 )
                        <li><a class="dropdown-item" href="{{ route('userinfo.index') }}">Manage Users</a></li>
                        <li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('manual.view') }}">View Guide</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="post" class="my-0">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

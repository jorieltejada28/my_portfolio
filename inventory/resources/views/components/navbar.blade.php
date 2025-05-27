<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ Auth::check() ? route('dashboard') : url('/') }}">MyApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="inventoryDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Inventory
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="inventoryDropdown">
                            <li><a class="dropdown-item" href="{{ route('inventory_get') }}">List</a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory_archive') }}">Archive</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Sign Out
                                </a>
                                <form id="logout-form" action="{{ route('sign_out') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    @php
                        $guestLinks = [
                            ['label' => 'Home', 'url' => url('/')],
                            ['label' => 'Sign In', 'url' => route('sign_in')],
                            ['label' => 'Sign Up', 'url' => route('sign_up')],
                        ];
                    @endphp

                    @foreach ($guestLinks as $link)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</nav>

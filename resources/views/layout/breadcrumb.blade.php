<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ $breadcrumbs['title'] ?? 'Dashboard' }}</h4>
                <ul class="breadcrumbs pull-left">
                    @if(isset($breadcrumbs['list']) && is_array($breadcrumbs['list']))
                        @foreach($breadcrumbs['list'] as $item)
                            <li>
                                @if ($loop->last)
                                    <span>{{ ucfirst($item) }}</span>
                                @else
                                    {{-- Kalau ada URL, bisa kamu buat di $breadcrumbs['urls'][$loop->index] --}}
                                    <a href="#">{{ ucfirst($item) }}</a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <div class="col-sm-6 clearfix">
            <div class="user-profile pull-right dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <img class="avatar user-thumb mr-2" src="{{ Auth::user()->foto && file_exists(public_path('uploads/foto/' . Auth::user()->foto))
    ? asset('uploads/foto/' . Auth::user()->foto)
    : asset('srtdash/assets/images/author/avatar.png') }}" alt="avatar" width="40" height="40"
                        style="border-radius: 50%;">
                    <span class="user-name">{{ Auth::user()->nama }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile.show') }}">Profil Saya</a>
                    <a class="dropdown-item" href="{{ route('profile.setting') }}">Settings</a>
                    <a class="dropdown-item" href="{{ url('/logout') }}">Log Out</a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
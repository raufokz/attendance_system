<!-- Top Bar Start -->
   <div class="topbar">

<!-- LOGO -->
    <div class="topbar-left">
        <a href="/" class="logo">
            <span id="clock-container">
                <div class="rectangular-clock">
                    <div class="clock-face">
                        <!-- Clock numbers -->
                        <span class="number number1">1</span>
                        <span class="number number2">2</span>
                        <span class="number number3">3</span>
                        <span class="number number4">4</span>
                        <span class="number number5">5</span>
                        <span class="number number6">6</span>
                        <span class="number number7">7</span>
                        <span class="number number8">8</span>
                        <span class="number number9">9</span>
                        <span class="number number10">10</span>
                        <span class="number number11">11</span>
                        <span class="number number12">12</span>
                        <!-- Clock hands -->
                        <div class="hand hour-hand"></div>
                        <div class="hand minute-hand"></div>
                        <div class="hand second-hand"></div>
                        <div class="center-circle"></div>
                    </div>
                </div>
            </span>
            <i>
                <i class="mdi mdi-clock"></i>
            </i>
        </a>
    </div>
<nav class="navbar-custom">
    <ul class="navbar-right d-flex list-inline float-right mb-0">
        <!-- <li class="dropdown notification-list d-none d-md-block">
            <form role="search" class="app-search">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" placeholder="Search..">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </li> -->

        <!-- full screen -->
        <li class="dropdown notification-list d-none d-md-block">
            <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                <i class="mdi mdi-fullscreen noti-icon"></i>
            </a>
        </li>
        <li>

</li>

        <li class="dropdown notification-list">
            <div class="dropdown notification-list nav-pro-img">
                <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        @auth
                            @if(auth()->user()->profile_picture)
                                <img src="{{ Storage::url(auth()->user()->profile_picture) }}" alt="Profile" class="rounded-circle profile-img">
                            @else
                                <img src="{{ asset('assets/images/profile-icon.png') }}" alt="Profile" class="rounded-circle profile-img">
                            @endif
                        @endauth

                   @auth
    @if (auth()->user()->hasRole('admin'))
        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 1rem; color: #fff; padding: 5px 10px; border-radius: 5px;">Administrator</span>
    @else
        <span class="badge bg-primary fw-semibold" style="font-size: 1rem; color: #fff; padding: 5px 10px; border-radius: 5px;">User</span>

    @endif


                @endauth
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <!-- <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5"></i> Profile</a> -->

                    {{-- <a class="dropdown-item d-block" href="#"><span class="badge badge-success float-right">11</span><i class="mdi mdi-settings m-r-5"></i> Settings</a> --}}
                          <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="mdi mdi-account-edit m-r-5"></i> Edit Profile
                        </a>
                    <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5"></i> Lock screen</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="mdi mdi-power text-danger"></i> {{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                </div>
            </div>
        </li>

    </ul>

    <ul class="list-inline menu-left mb-0">
        <li class="float-left">
            <button class="button-menu-mobile open-left waves-effect">
                <i class="mdi mdi-menu"></i>
            </button>
        </li>
        {{-- <li class="d-none d-sm-block">
            <div class="dropdown pt-3 d-inline-block">
                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Create
                    </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Separated link</a>
                </div>
            </div>
        </li> --}}
    </ul>

</nav>

</div>
<!-- Top Bar End -->

<style>
#clock-container {
    display: inline-block;
    margin-right: 10px;
}

.rectangular-clock {
    width: 200px;
    height: 100px;
    background-color: #fff;
    border: 2px solid #3bafda;
    border-radius: 8px;
    position: relative;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.clock-face {
    width: 100%;
    height: 100%;
    position: relative;
}

.number {
    position: absolute;
    font-size: 12px;
    font-weight: bold;
    color: #333;
    transform: translate(-50%, -50%);
}

/* Corrected number positions for rectangular clock (180px width, 90px height) */
.number1 { top: 15%; left: 70%; }       /* 1 o'clock position */
.number2 { top: 15%; left: 85%; }       /* 2 o'clock position */
.number3 { top: 50%; left: 90%; }       /* 3 o'clock position */
.number4 { top: 80%; left: 85%; }       /* 4 o'clock position */
.number5 { top: 85%; left: 70%; }       /* 5 o'clock position */
.number6 { top: 85%; left: 50%; }       /* 6 o'clock position */
.number7 { top: 85%; left: 30%; }       /* 7 o'clock position */
.number8 { top: 80%; left: 15%; }       /* 8 o'clock position */
.number9 { top: 50%; left: 5%; }        /* 9 o'clock position */
.number10 { top: 15%; left: 15%; }      /* 10 o'clock position */
.number11 { top: 15%; left: 30%; }      /* 11 o'clock position */
.number12 { top: 15%; left: 50%; }      /* 12 o'clock position */

.hand {
    position: absolute;
    transform-origin: bottom center;
    left: 50%;
    bottom: 50%;
    border-radius: 2px;
}

.hour-hand {
    width: 4px;
    height: 25px;
    background-color: #3bafda;
    margin-left: -2px;
}

.minute-hand {
    width: 2px;
    height: 35px;
    background-color: #555;
    margin-left: -1px;
}

.second-hand {
    width: 1px;
    height: 40px;
    background-color: #e74c3c;
    margin-left: -0.5px;
}

.center-circle {
    position: absolute;
    width: 8px;
    height: 8px;
    background-color: #333;
    border-radius: 50%;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
}
</style>
<script>
function updateClock() {
    const now = new Date();
    const hour = now.getHours() % 12;
    const minute = now.getMinutes();
    const second = now.getSeconds();

    const hourDeg = (hour * 30) + (minute * 0.5);
    const minuteDeg = minute * 6;
    const secondDeg = second * 6;

    document.querySelector('.hour-hand').style.transform = `rotate(${hourDeg}deg)`;
    document.querySelector('.minute-hand').style.transform = `rotate(${minuteDeg}deg)`;
    document.querySelector('.second-hand').style.transform = `rotate(${secondDeg}deg)`;
}

updateClock();
setInterval(updateClock, 1000);
</script>

@php use Illuminate\Support\Facades\Auth; @endphp
@push('styles')
    <style>

        .text{
            color: var(--text-color);
        }
        .logoHaupt {
            height: 100px;
            max-width: 140px;
        }
        .header-bar {
            background: var(--surface-color);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            margin: 10px 20px;
            max-width: 2400px;
        }

        .nav-select {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            flex: 1;
            justify-content: center;
        }

        .nav-button {
            background: var(--primary-color);;
            color: white;
            display: flex;
            align-items: center;
            padding: 8px 28px;
            text-decoration: none;
            font-size: var(--font-size);
            border-radius: 15px;
            cursor: pointer;
            box-shadow: 1px 1px 5px var(--shadow);
            transition: all 0.3s ease;
        }

        .nav-button:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }

        .dropbtn {
            padding: 10px;
            display: none;
            flex-direction: row;
            gap: 5px;
            align-items: center;

            background-color: var(--primary-color);;
            color: white;
            border: none;

            border-radius: 15px;
            cursor: pointer;
            box-shadow: 1px 1px 5px var(--shadow);
            transition: all 0.3s ease;
        }


        .dropdown-content {
            border-radius: 15px;
            display: none;
            position: absolute;
            background-color: var(--dropdown);
            min-width: 200px;
            box-shadow:  0 0 5px var(--shadow) inset;

        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .dropdown:hover .dropdown-content {
            display: flex;
            flex-direction: column;
        }

        .dropdown a:hover {
            border-radius: 15px;
            background:	var(--hover-color);
            transform: translateY(-2px);
        }

        .dropdown-link {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .dropbtn:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
        }

        .dropbtn > img {
            display: block;
            margin: 0;
            padding: 0;
        }

        .search {
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            box-shadow: 1px 1px 5px var(--shadow);
        }
        .hidden{
            display: none;
        }

        .search-container{
            align-items: center;
        }
        .search-input{
            height: auto;
            line-height: normal;
            padding: 8px 8px;
            margin: 0;
            border: none;
            outline: none;
            border-radius: 15px;
        }
        .search-button{
            background: var(--primary-color);
            border: none;
            outline: none;
        }

        .logout-img{
            background: none;
            border: none;
            margin: 0;

        }
        .mobile-menu-symbol {
            display:none;
        }

        .mobile-menu-symbol > button {
            background: none;
            border: none;
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            justify-content: center;
            margin: 10px 20px;
        }

        .mobile-menu-icon{
            filter: brightness(0) invert(1);
        }

        .mobile-menu > div {
            display: flex;
            flex-direction: row;
            justify-content: center;
            padding: 10px 0;
            background: var(--surface-color);
        }

        .mobile-menu > div:first-child{
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .mobile-menu > div:last-child{
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .mobile-menu > div:hover {
            background: #4a5568;
        }

        .mobile-view-list {
            text-decoration: none;
            color: var(--primary-color);
        }

        .mobile-logout {
            background: none;
            border: none;
            width: 100%;
            box-sizing: border-box;
            font-weight: bold;
            color: var(--primary-color);
            font-size: var(--font-size);
        }

        .mobile-logout-form{
            display: flex;
        }

        @media (max-width: 900px){

            .nav-select {
                display: none;
            }

            .mobile-menu-symbol {
                display: block;
            }
        }
    </style>
@endpush
<div class="header-bar">
    <a href="/"><img src="images/Logo_A4.png" class="logoHaupt" alt="Logo"></a>
    <nav class="nav-select">
        <a href="/" class="nav-button">Homepage</a>
        <a href="/fachauswahl" class="nav-button">Fächer</a>
        <a href="/wochenaufgaben" class="nav-button">Wochenaufgaben</a>
        <form action ="/search" method="GET" class="search hidden" id="search" >
            <div class="search-container">
            <input type="text" name="query" class="search-input"
                   placeholder="Suche..." value="{{request('query')??''}}">
            <button type="submit" class="search-button">🔎</button>
            </div>
        </form>
        <a href="/login" class="nav-button" id="loginbtn">Login</a>

        <div class="dropdown">
            <button class="dropbtn" id="dropbtn">
                <span><span>{{ Auth::user()->name ?? '' }}
                        [{{ ucfirst(Auth::user()->role ?? '') }}]</span></span>
                <img src="images/account.svg" alt="account" width="15"/>
            </button>
            <div class="dropdown-content">
                <a href="/settings" class="dropdown-link">
                    <img src="images/settings.svg" alt="settings"/>
                    <p class="text" >Einstellungen</p>
                </a>
                <a class="dropdown-link">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-img"><img src="images/logout.svg" alt="logout"/></button>
                        <button type="submit" class="logout">Abmelden</button>
                    </form>
                </a>
            </div>
        </div>
    </nav>
    <div class="mobile-menu-symbol"><button onclick="getMobileViewMenu()"><img src="images/menu.png" alt="menu" class="logo" width="60"></button></div>
    <script>
            let loggedIn = @json(Auth::check());

            if(!loggedIn) {document.getElementById("dropbtn").style.display = "none";
                document.getElementById("loginbtn").style.display = "flex";
                document.getElementById("search").style.display = "none"}
            else {document.getElementById("loginbtn").style.display = "none";
                document.getElementById("dropbtn").style.display = "flex";
                document.getElementById("search").style.display="inline-block";}
    </script>
    <script>
        function getMobileViewMenu() {
            let menu = document.getElementById("mobile-menu");
            let currentDisplay = window.getComputedStyle(menu).display;

            if(currentDisplay === "none") {
                menu.style.display = "flex";
            } else {
                menu.style.display = "none";
            }
        }
    </script>
</div>
    <div class="mobile-menu" id="mobile-menu">
        <div><a href="/" class="mobile-view-list">Homepage</a></div>
        <div><a href="/fachauswahl" class="mobile-view-list">Fächer</a></div>
        <div><a href="/wochenaufgaben" class="mobile-view-list">Wochenaufgaben</a></div>
        @if(Auth::check())
            <div>
                <form action ="/search" method="GET" class="search" id="search" >
                    <div class="search-container">
                        <input type="text" name="query" class="search-input"
                               placeholder="Suche..." value="{{request('query')??''}}">
                        <button type="submit" class="search-button">🔎</button>
                    </div>
                </form>
            </div>
            <div><a href="/settings" class="mobile-view-list">Einstellungen</a></div>
            <div class="mobile-logout-form">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-logout">Abmelden</button>
                </form>
            </div>

            @else
            <div><a href="/login" class="mobile-view-list">Login</a></div>
        @endif
</div>

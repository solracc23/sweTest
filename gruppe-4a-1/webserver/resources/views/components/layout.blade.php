@push('styles')
    <!--Styling for Headings-->
    <style>
        * {
            font-size: var(--font-size);
        }
        h1 {
            color: #236C93;
            border-bottom: 2px solid #f0f0f0;
            margin: 20px;
            padding-top: 20px;
        }

        .alert {
            display: flex;
            border-radius: 15px;
            bottom: 0;
            align-items: center;
            padding: 0 10px;
            text-align: center;
            margin: 10px 20px;
            max-width: 2400px;
        }

        .alert-success {
            background: green;
        }

        .alert-fail {
            background: red;
        }

        .alert > div {
            padding: 10px 20px;
            color: white;
        }


    </style>
@endpush
@include('components.nav')

@if(session('success'))
    <div class="alert alert-success" id="alert">
        <img src="images/loggedin.svg">
        <div>{{ session('success') ?? '' }}</div>
    </div>
@endif

@if(session('register'))
    <div class="alert alert-success" id="alert">
        <img src="images/loggedin.svg">
        <div>{{session('register')}}</div>
    </div>
@endif

@if(request()->query('logout'))
    <div class="alert alert-fail" id="alert">
        <img src="images/logout-notification.svg">
        <div>Sie wurden erfolgreich abgemeldet.</div>
    </div>
@endif

@if(session('status'))
    <div class="alert alert-fail" id="alert">
        <img src="images/barrier.svg">
        <div>Darauf hast du leider keinen Zugriff. Bitte melde dich oben rechts unter "Login" an.</div>
    </div>
@endif
@if(session('failure'))
    <div class="alert alert-fail" id="alert">
        <img src="images/barrier.svg">
        <div>Irgendwas ist schief gelaufen.</div>
    </div>
@endif

@component('components.content')
    {!! $slot !!}
@endcomponent()
@include('components.footer')

<script>
    setTimeout(function () {
            let alert = document.getElementById('alert')

            if (alert != null) {
                document.getElementById('alert').style.display = "none";
                console.log("erfolgreich.")
            }

            const url = new URL(window.location);
            url.searchParams.delete('logout'); // Parameter entfernen
            window.history.replaceState({}, document.title, url);

        }, 20000
    );
</script>

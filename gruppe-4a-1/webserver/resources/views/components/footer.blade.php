@push('styles')
    <style>
        footer {
            display: flex;
            border-radius: 15px;
            bottom: 0;
            background-color: var(--surface-color);
            justify-content: center;
            color: white;
            text-align: center;
            padding: 0 20px;
            margin: 10px 20px;
            max-width: 2400px;
        }
        .footerlist ul {
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            list-style: none;
            gap: 10px;
        }

        footer ul li {
            padding: 0 15px;
            border-right: 1px solid var(--text-color);;
            display: inline;
        }

        footer ul li a {
            text-decoration: none;
            color: var(--primary-color);;
            font-size: var(--font-size);
        }
        footer ul li a:hover{
            color: var(--hover-color);
        }

        footer ul li:last-child {
            border-right: none;
        }

        footer ul {
            padding: 0;
            display: flex;
            flex-direction: row;
            justify-content: center;
            list-style: none;
        }
    </style>
@endpush
<footer>
    <div class="footerlist">
    <ul>
        <li><a href="/impressum">Impressum</a></li>
        <li><a href="/kontakt">Kontakt</a></li>
        <li><a href="/datenschutz">Datenschutz</a></li>
    </ul>
    </div>
</footer>

@push('styles')
    <style>
        .content {
            border-radius: 15px;
            display: flex;
            padding: 10px 10px;
            margin: 10px 20px;
            max-width: 2400px;
            color: black;
            flex: 1 0 auto;
            background: var(--surface-color);
            justify-content: center;
        }

        h1 {
            color: #236C93;
            border-bottom: 2px solid #f0f0f0;
            margin: 20px;
            padding-top: 20px;
        }

        a {
            font-weight: bold;
            font-size: var(--font-size);
        }
    </style>
 @endpush
<div class="content">{!! $slot !!}</div>

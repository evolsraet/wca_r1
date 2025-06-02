<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .error-container {
            text-align: center;
            max-width: 480px;
            padding: 2rem;
        }

        .error-code {
            font-size: 6rem;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.5rem;
            font-weight: 500;
            color: #e3342f;
            margin-bottom: 0.5rem;
        }

        .error-description {
            font-size: 1rem;
            color: #555;
            margin-bottom: 2rem;
        }

        .btn-back-home {
            display: inline-block;
            background-color: #e3342f;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.95rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background 0.3s ease;
        }

        .btn-back-home:hover {
            background-color: #cc1f1a;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">@yield('code')</div>
        <div class="error-message">@yield('message')</div>
        <div class="error-description">@yield('description')</div>
        @yield('button')
    </div>
</body>
</html>
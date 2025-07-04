<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hashing Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-700">Password Hashing Tool</h1>

        @if (session('hashed_password'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <p class="font-bold">Password Asli:</p>
                <p class="mb-2 break-all">{{ session('original_password') }}</p>
                <p class="font-bold">Hasil Hash:</p>
                <p class="text-sm break-all" id="hashed_password">{{ session('hashed_password') }}</p>
                <button onclick="copyToClipboard()" class="mt-2 bg-green-500 text-white px-2 py-1 text-xs rounded">Copy Hash</button>
            </div>
        @endif

        <form action="{{ route('dev.hash_tool.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    Masukkan Password:
                </label>
                <input type="text" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Generate Hash
                </button>
            </div>
        </form>
    </div>

    <script>
        function copyToClipboard() {
            const textToCopy = document.getElementById('hashed_password').innerText;
            navigator.clipboard.writeText(textToCopy).then(() => {
                alert('Hashed password copied to clipboard!');
            }, (err) => {
                alert('Failed to copy!');
            });
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
</head>
<body>
    <h4>QR Code generator</h4>
    テスト
    <img src="data:image/png;base64, {{ base64_encode($qrCode) }}" alt="QR Code">
</body>
</html>
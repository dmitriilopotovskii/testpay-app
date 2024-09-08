<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
</head>
<body>
<h1>Payment Successful</h1>

<p>Dear,</p>

<p>Your payment has been successfully processed.</p>

<p>Thank you for your purchase!</p>
<ul>

    @foreach ($cards as $card)

        <li>{{ $card['cardNumber']}}  </li>
    @endforeach
</ul>
<p>Best regards,</p>
<p>Your Company Name</p>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Delivered</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9; padding:20px;">
        <tr>
            <td align="center">

                <!-- Main Card -->
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#28a745,#218838); padding:30px; text-align:center; color:#ffffff;">
                            <h1 style="margin:0; font-size:24px;">🎉 Order Delivered!</h1>
                            <p style="margin:5px 0 0; font-size:14px;">Your package has arrived</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:30px; text-align:center;">

                            <h2 style="margin-bottom:10px; color:#333;">
                                Order #{{ $order->id }}
                            </h2>

                            <p style="color:#555; font-size:15px; line-height:1.6;">
                                Your order has been successfully delivered.  
                                We hope you love your purchase!
                            </p>

                            <p style="color:#555; font-size:15px; margin-top:20px;">
                                We'd really appreciate it if you could take a moment to share your experience.
                            </p>

                            <!-- Button -->
                            <a href="{{ route('review.create', $order->id) }}"
                               style="display:inline-block; margin-top:25px; padding:12px 30px; background:#28a745; color:#ffffff; text-decoration:none; border-radius:5px; font-weight:bold; font-size:14px;">
                                ⭐ Leave a Review
                            </a>

                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:0 30px;">
                            <hr style="border:none; border-top:1px solid #eee;">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:20px 30px; text-align:center; font-size:13px; color:#888;">
                            <p style="margin:0;">Thank you for shopping with us ❤️</p>
                            <p style="margin:5px 0 0;">If you have any questions, feel free to contact us.</p>
                        </td>
                    </tr>

                </table>

                <!-- Bottom spacing -->
                <div style="height:20px;"></div>

            </td>
        </tr>
    </table>

</body>
</html>
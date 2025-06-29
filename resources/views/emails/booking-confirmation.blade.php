<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .ticket { background: white; border: 2px dashed #dee2e6; padding: 20px; margin: 20px 0; border-radius: 10px; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 14px; }
        .qr-code { text-align: center; margin: 20px 0; }
        .qr-code img { border: 1px solid #ddd; border-radius: 5px; max-width: 200px; }
        .ticket-details { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .ticket-details p { margin: 8px 0; }
        .status-confirmed { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 Booking Confirmation</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $booking->user->name }}!</h2>
            
            <p>Your booking has been confirmed successfully. Here are your ticket details:</p>
            
            <div class="ticket">
                <h3 style="text-align: center; color: #007bff; margin-bottom: 20px;">Event Ticket</h3>
                
                <div class="ticket-details">
                    <p><strong>Event:</strong> {{ $booking->event->title }}</p>
                    <p><strong>Venue:</strong> {{ $booking->event->venue }}</p>
                    <p><strong>Date:</strong> {{ $booking->event->date->format('l, F d, Y') }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->event->time)->format('g:i A') }}</p>
                    <p><strong>Ticket Number:</strong> <span style="font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 3px;">{{ $booking->ticket_number }}</span></p>
                    <p><strong>Seats Booked:</strong> {{ $booking->quantity }}</p>
                    <p><strong>Status:</strong> <span class="status-confirmed">{{ ucfirst($booking->status) }}</span></p>
                </div>
                
                <div class="qr-code">
                    <h4 style="margin-bottom: 15px; color: #495057;">QR Code for Entry</h4>
                    @if(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate($booking->getQrCodeString()) !!}
                    @else
                        <div style="background: #f8f9fa; border: 2px dashed #dee2e6; padding: 20px; border-radius: 5px;">
                            <p style="color: #6c757d; font-style: italic; margin: 0;">QR Code: {{ $booking->ticket_number }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div style="background: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin: 20px 0; border-radius: 0 5px 5px 0;">
                <p style="margin: 0; color: #0056b3;"><strong>Important:</strong> Please bring this ticket number and QR code with you to the event. You may be asked to show your ticket for entry.</p>
            </div>
            
            <p>If you have any questions, please contact our support team.</p>
            
            <p>Thank you for choosing our event management system!</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Event Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 
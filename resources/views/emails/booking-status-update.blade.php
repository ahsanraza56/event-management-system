<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .status-update { background: white; border: 2px solid #dee2e6; padding: 20px; margin: 20px 0; border-radius: 10px; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 14px; }
        .status-badge { padding: 0.5rem 1rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: inline-block; }
        .status-confirmed { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .status-cancelled { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .ticket-details { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .ticket-details p { margin: 8px 0; }
        .status-change { background: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin: 20px 0; border-radius: 0 5px 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Booking Status Update</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $booking->user->name }}!</h2>
            
            <p>Your booking status has been updated by our admin team. Here are the details:</p>
            
            <div class="status-change">
                <p style="margin: 0; color: #0056b3;">
                    <strong>Status Changed:</strong> 
                    <span style="text-decoration: line-through; color: #6c757d;">{{ ucfirst($oldStatus) }}</span> 
                    → 
                    <span class="status-badge status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</span>
                </p>
            </div>
            
            <div class="status-update">
                <h3 style="text-align: center; color: #007bff; margin-bottom: 20px;">Booking Details</h3>
                
                <div class="ticket-details">
                    <p><strong>Event:</strong> {{ $booking->event->title }}</p>
                    <p><strong>Venue:</strong> {{ $booking->event->venue }}</p>
                    <p><strong>Date:</strong> {{ $booking->event->date->format('l, F d, Y') }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->event->time)->format('g:i A') }}</p>
                    <p><strong>Ticket Number:</strong> <span style="font-family: monospace; background: #e9ecef; padding: 2px 6px; border-radius: 3px;">{{ $booking->ticket_number }}</span></p>
                    <p><strong>Seats Booked:</strong> {{ $booking->quantity }}</p>
                    <p><strong>Current Status:</strong> <span class="status-badge status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</span></p>
                </div>
            </div>
            
            @if($newStatus === 'confirmed')
                <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 0 5px 5px 0;">
                    <p style="margin: 0; color: #155724;"><strong>✅ Confirmed!</strong> Your booking has been confirmed. You can now attend the event.</p>
                </div>
            @elseif($newStatus === 'pending')
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 0 5px 5px 0;">
                    <p style="margin: 0; color: #856404;"><strong>⏳ Pending Review</strong> Your booking is under review. We'll notify you once it's confirmed.</p>
                </div>
            @elseif($newStatus === 'cancelled')
                <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; border-radius: 0 5px 5px 0;">
                    <p style="margin: 0; color: #721c24;"><strong>❌ Cancelled</strong> Your booking has been cancelled. If you have any questions, please contact our support team.</p>
                </div>
            @endif
            
            <p>If you have any questions about this status change, please contact our support team.</p>
            
            <p>Thank you for choosing our event management system!</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Event Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 
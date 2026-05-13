<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;">
    <div style="background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #E8642A; margin: 0;">New Booking Request</h1>
        </div>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Hello {{ $booking->caterer->business_name ?? $booking->caterer->name }},
        </p>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            You have received a new booking request from <strong>{{ $booking->user->name }}</strong>.
        </p>

        <div style="background-color: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #1C1A17; margin-top: 0;">Booking Details:</h3>
            <p style="margin: 10px 0;"><strong>Event Title:</strong> {{ $booking->event_title }}</p>
            <p style="margin: 10px 0;"><strong>Event Date:</strong> {{ $booking->event_date->format('F d, Y') }}</p>
            <p style="margin: 10px 0;"><strong>Number of Guests:</strong> {{ $booking->guests }}</p>
            @if($booking->selected_package_name)
                <p style="margin: 10px 0;"><strong>Package:</strong> {{ $booking->selected_package_name }}</p>
            @endif
            @if($booking->special_requests)
                <p style="margin: 10px 0;"><strong>Event Notes:</strong> {{ $booking->special_requests }}</p>
            @endif
            <p style="margin: 10px 0;"><strong>Status:</strong> <span style="background-color: #FFF8E1; color: #F57F17; padding: 5px 10px; border-radius: 4px;">Pending</span></p>
        </div>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Please log in to your dashboard to review and respond to this booking request.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('caterer.bookings') }}" style="background-color: #E8642A; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                View Booking
            </a>
        </div>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="color: #8A7F72; font-size: 12px; text-align: center;">
            This is an automated message from PlatePal. Please do not reply to this email.
        </p>
    </div>
</div>

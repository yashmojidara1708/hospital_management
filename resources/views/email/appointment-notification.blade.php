<!DOCTYPE html>
<html>
<head>
    <title>Appointment Notification</title>
</head>
<body>
    <h1>Appointment {{ ucfirst($type) }}</h1>
    
    <p>Dear {{ $appointment->patient_name }},</p>
    
    @if($type === 'approved')
        <p>Your appointment scheduled for {{ $appointment->appointment_date }} has been approved.</p>
    @else
        <p>We regret to inform you that your appointment scheduled for {{ $appointment->appointment_date }} has been rejected.</p>
        @if($reason)
            <p>Reason: {{ $reason }}</p>
        @endif
    @endif
    
    <p>Thank you,</p>
    <p>Hospital Management Team</p>
</body>
</html>
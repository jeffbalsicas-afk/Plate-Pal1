<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;">
    <div style="background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #E8642A; margin: 0;">Password Changed</h1>
        </div>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Hello {{ $user->business_name ?? $user->name }},
        </p>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            This is a confirmation that your PlatePal account password was changed.
        </p>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            If you made this change, no further action is needed.
        </p>

        <div style="background-color: #FFF7ED; border: 1px solid #FED7AA; padding: 16px; border-radius: 8px; margin: 20px 0;">
            <p style="color: #9A3412; font-size: 14px; line-height: 1.6; margin: 0;">
                If you did not change your password, reset it immediately or contact PlatePal support.
            </p>
        </div>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="color: #8A7F72; font-size: 12px; text-align: center;">
            This is an automated message from PlatePal. Please do not reply to this email.
        </p>
    </div>
</div>

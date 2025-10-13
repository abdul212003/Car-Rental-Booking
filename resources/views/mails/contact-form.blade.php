<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>RJ Car Rental Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }

        .field {
            margin-bottom: 15px;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 80px;
        }

        .message-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Form Submission</h1>
        </div>
        <div class="content">
            <div class="field">
                <span class="label">Name:</span>
                <span>{{ $contact->contact_name }}</span>
            </div>
            <div class="field">
                <span class="label">Email:</span>
                <span>{{ $contact->contact_email }}</span>
            </div>
            <div class="field">
                <span class="label">Subject:</span>
                <span>{{ $contact->contact_subject }}</span>
            </div>
            <div class="field">
                <span class="label">Message:</span>
                <div class="message-box">
                    {{ $contact->contact_message }}
                </div>
            </div>
            <div class="field" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                <small>This email was sent from your website contact form on
                    {{ now()->format('F j, Y \a\t g:i A') }}</small>
            </div>
        </div>
    </div>
</body>

</html>

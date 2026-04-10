@extends('layouts.app')

@section('title', 'Privacy Policy — Next Student')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Privacy Policy</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Last updated: April 9, 2026</p>

        <div class="prose prose-sm dark:prose-invert max-w-none space-y-6 text-gray-700 dark:text-gray-300">

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">1. About This Policy</h2>
                <p>Next Student ("we," "us," or "our") is an independent academic help-request platform. We are not affiliated with, owned by, or operated by Kirkwood Community College. We currently serve Kirkwood Community College students and faculty exclusively, which is why registration requires a Kirkwood email address. This Privacy Policy explains what personal information we collect, how we use it, and your rights regarding that information.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">2. Information We Collect</h2>
                <p>When you register for an account we collect:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>First and last name</li>
                    <li>Kirkwood College email address (required to verify your enrollment or employment)</li>
                    <li>Mobile phone number</li>
                    <li>Academic major (students) or department (instructors)</li>
                    <li>Badge or ID photo (instructors only, for identity verification)</li>
                    <li>Password (stored as a one-way cryptographic hash — never readable)</li>
                </ul>
                <p class="mt-2">We also automatically collect standard server logs (IP address, browser type, pages visited, timestamps) for security and operational purposes.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">3. How We Use Your Information</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Account authentication</strong> — to verify your identity when you log in.</li>
                    <li><strong>Email verification</strong> — to confirm your Kirkwood email address before activating your account.</li>
                    <li><strong>SMS notifications</strong> — to send you text message alerts about your help requests (assignment confirmations, status updates, completions) via Twilio. Message frequency varies based on your activity. Message &amp; data rates may apply. Reply STOP to opt out at any time.</li>
                    <li><strong>Admin review</strong> — instructor accounts require manual approval; badge photos are used solely for this purpose and are not shared publicly.</li>
                    <li><strong>Help-request management</strong> — to connect students with the appropriate instructors.</li>
                    <li><strong>System security</strong> — to detect and prevent unauthorized access or abuse.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">4. SMS / Text Messaging</h2>
                <p>By providing your mobile phone number and creating an account, you consent to receive automated SMS notifications from Next Student related to your help requests. These messages are transactional in nature (not marketing). To stop receiving SMS messages, reply <strong>STOP</strong> to any message or update your notification preferences in Settings. For help, reply <strong>HELP</strong> or contact us at the address below.</p>
                <p class="mt-2">We use <strong>Twilio</strong> to deliver SMS messages. Your phone number is transmitted to Twilio solely for message delivery; Twilio's privacy policy governs their handling of that data.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">5. Sharing of Information</h2>
                <p>We do not sell your personal information. We share data only with:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li><strong>Twilio</strong> — phone numbers for SMS delivery.</li>
                    <li><strong>Next Student platform administrators</strong> — account details necessary for account approval and platform administration.</li>
                    <li><strong>Law enforcement or regulators</strong> — only when required by law.</li>
                </ul>
                <p class="mt-2">We do not share your information with Kirkwood Community College or any third party outside of the above.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">6. Data Retention</h2>
                <p>Your account data is retained for as long as your account is active. You may request deletion of your account at any time through Settings → Delete Account. Backups may retain data for up to 30 days after deletion.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">7. Security</h2>
                <p>We use industry-standard measures including encrypted connections (HTTPS), hashed passwords, rate-limited login, and session management to protect your data. No method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">8. Your Rights</h2>
                <p>You may access, update, or correct your personal information through your Profile page. To request full data deletion, use Settings → Delete Account or contact us directly.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">9. Changes to This Policy</h2>
                <p>We may update this policy from time to time. We will notify registered users of material changes via email. Continued use of the platform after changes constitutes acceptance of the revised policy.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">10. Contact</h2>
                <p>Questions about this Privacy Policy? Contact us at:</p>
                <p class="mt-1"><strong>Next Student</strong><br>
                Email: <a href="mailto:support@nextstudent.org" class="text-blue-600 hover:underline">support@nextstudent.org</a></p>
            </section>

        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex gap-4 text-sm">
            <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:underline">Terms &amp; Conditions</a>
            <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Back to Register</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Terms & Conditions — Next Student')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Terms &amp; Conditions</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Last updated: April 9, 2026</p>

        <div class="prose prose-sm dark:prose-invert max-w-none space-y-6 text-gray-700 dark:text-gray-300">

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">1. Acceptance of Terms</h2>
                <p>By registering for or using Next Student ("the Platform"), you agree to be bound by these Terms &amp; Conditions. If you do not agree, do not use the Platform.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">2. About Next Student</h2>
                <p>Next Student is an independent academic help-request platform. We are not affiliated with, endorsed by, or operated by Kirkwood Community College. We currently serve Kirkwood Community College students and faculty exclusively and require a Kirkwood email address solely to verify that users belong to the institution we serve. Kirkwood Community College bears no responsibility for the Platform or its contents.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">3. Eligibility</h2>
                <p>The Platform is available to currently enrolled Kirkwood Community College students and active Kirkwood faculty. You must use your official Kirkwood email address to register. Accounts found to be ineligible may be suspended without notice.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">4. Account Responsibilities</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>You are responsible for maintaining the confidentiality of your password.</li>
                    <li>You must provide accurate, current information when registering.</li>
                    <li>You may not share your account credentials with others.</li>
                    <li>Notify us immediately if you suspect unauthorized use of your account.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">5. Acceptable Use</h2>
                <p>You agree to use the Platform only for its intended purpose: submitting and responding to academic help requests. You must not:</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Submit false, misleading, or abusive help requests.</li>
                    <li>Harass or threaten other users.</li>
                    <li>Attempt to gain unauthorized access to other accounts or system resources.</li>
                    <li>Use the Platform for any commercial or non-academic purpose.</li>
                    <li>Circumvent rate limits, authentication, or other security controls.</li>
                </ul>
                <p class="mt-2">Violations may result in immediate account suspension.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">6. SMS Notifications</h2>
                <p>By providing your mobile phone number during registration, you consent to receive automated SMS text messages from Next Student regarding your help requests. These are transactional notifications (e.g., request assigned, status updated, request completed).</p>
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Message frequency depends on your platform activity.</li>
                    <li>Standard message and data rates from your carrier may apply.</li>
                    <li>To opt out, reply <strong>STOP</strong> to any SMS or disable SMS notifications in Settings.</li>
                    <li>For support, reply <strong>HELP</strong> to any SMS.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">7. Instructor Account Verification</h2>
                <p>Instructor accounts require submission of a badge or ID photo for identity verification by a Next Student administrator. This photo is used solely for verification and will not be shared publicly. Submitting a photo that misrepresents your identity is grounds for permanent account ban.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">8. Intellectual Property</h2>
                <p>The Next Student platform, including its design, code, and content, is the property of Next Student. You may not reproduce, distribute, or create derivative works without express written permission.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">9. Disclaimers</h2>
                <p>The Platform is provided "as is" without warranties of any kind. Next Student is not affiliated with Kirkwood Community College and makes no representations on their behalf. We do not guarantee uninterrupted availability or that help requests will be fulfilled within any particular timeframe.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">10. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, Next Student shall not be liable for any indirect, incidental, or consequential damages arising from your use of the Platform.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">11. Termination</h2>
                <p>We reserve the right to suspend or terminate your account at any time for violations of these Terms. You may delete your own account through Settings → Delete Account.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">12. Changes to These Terms</h2>
                <p>We may revise these Terms at any time. Material changes will be communicated via email. Continued use of the Platform after the effective date of revised Terms constitutes acceptance.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">13. Governing Law</h2>
                <p>These Terms are governed by the laws of the State of Iowa, without regard to conflict-of-law provisions.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">14. Contact</h2>
                <p><strong>Next Student</strong><br>
                Email: <a href="mailto:support@nextstudent.org" class="text-blue-600 hover:underline">support@nextstudent.org</a></p>
            </section>

        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex gap-4 text-sm">
            <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:underline">Privacy Policy</a>
            <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Back to Register</a>
        </div>
    </div>
</div>
@endsection

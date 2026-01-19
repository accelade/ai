# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take security vulnerabilities seriously. If you discover a security issue, please report it responsibly.

### How to Report

**Please do NOT report security vulnerabilities through public GitHub issues.**

Instead, please report them via one of the following methods:

1. **GitHub Security Advisories** (Preferred)
   - Go to the [Security tab](../../security/advisories) of this repository
   - Click "Report a vulnerability"
   - Fill out the form with details

2. **Email**
   - Send an email to: info@3x1.io
   - Use the subject line: `[SECURITY] Accelade AI Vulnerability Report`

### What to Include

Please include the following information in your report:

- **Type of vulnerability** (e.g., XSS, prompt injection, API key exposure, etc.)
- **Location** of the affected source code (file path, line numbers)
- **Steps to reproduce** the vulnerability
- **Proof of concept** or exploit code (if possible)
- **Impact** assessment of the vulnerability
- **Suggested fix** (if you have one)

### What to Expect

1. **Acknowledgment**: We will acknowledge receipt of your report within 48 hours.

2. **Investigation**: We will investigate the issue and determine its severity and impact.

3. **Updates**: We will keep you informed about our progress throughout the process.

4. **Resolution**: Once the issue is resolved, we will:
   - Release a security patch
   - Publish a security advisory
   - Credit you for the discovery (unless you prefer to remain anonymous)

### Timeline

We aim to:

- Acknowledge reports within **48 hours**
- Provide an initial assessment within **1 week**
- Release a patch within **30 days** for critical issues
- Release a patch within **90 days** for non-critical issues

### Safe Harbor

We consider security research conducted in accordance with this policy to be:

- Authorized and lawful
- Helpful to our security posture
- Exempt from legal action by us

We will not pursue legal action against researchers who:

- Act in good faith
- Avoid privacy violations and data destruction
- Do not exploit vulnerabilities beyond what's necessary for the report
- Report vulnerabilities promptly

## Security Best Practices

When using Accelade AI in your application:

### API Key Security

- **Never expose API keys** in client-side code or public repositories
- Store API keys in `.env` files and ensure `.env` is in `.gitignore`
- Use environment variables for all sensitive configuration
- Consider using Laravel's encrypted environment files for production

### Prompt Injection Prevention

- Sanitize user input before including in AI prompts
- Use system messages to establish context and boundaries
- Implement rate limiting on AI endpoints
- Log and monitor AI interactions for suspicious patterns

### Data Privacy

- Be cautious about what context is sent to AI providers
- Implement consent mechanisms if sending user data to AI services
- Consider data residency requirements for your users
- Review AI provider data handling policies

### Access Control

- Protect AI endpoints with authentication middleware
- Implement proper authorization for AI features
- Rate limit AI requests per user/session
- Monitor for unusual usage patterns

### Content Security Policy

Consider implementing a Content Security Policy header:

```php
// In a middleware
$response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'");
```

## Vulnerability Disclosure

After a vulnerability has been fixed, we will:

1. Release a new version with the patch
2. Publish a security advisory on GitHub
3. Update this document if needed
4. Notify users through appropriate channels

## Contact

For security-related questions that are not vulnerabilities, please open a regular GitHub issue or discussion.

Thank you for helping keep Accelade AI and its users safe!

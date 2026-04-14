<?php
/**
 * Systemiks - Email helper (PHP mail() or SMTP from settings)
 * Load after config.php and database.php so get_setting() is available.
 */

if (!function_exists('get_setting')) {
    return;
}

/**
 * Send an email. Uses SMTP if configured in settings, otherwise PHP mail().
 * Returns true on success, false on failure.
 */
function send_systemiks_mail(string $to, string $subject, string $bodyPlain, string $bodyHtml = ''): bool {
    $fromEmail = get_setting('smtp_from_email', get_setting('company_email', 'noreply@' . ($_SERVER['SERVER_NAME'] ?? 'localhost')));
    $fromName  = get_setting('smtp_from_name', get_setting('company_name', 'Systemiks'));

    $smtpHost = get_setting('smtp_host');
    if ($smtpHost !== '') {
        return send_mail_smtp(
            $smtpHost,
            (int) get_setting('smtp_port', '587'),
            get_setting('smtp_user'),
            get_setting('smtp_password'),
            get_setting('smtp_encryption'), // tls, ssl, or empty
            $fromEmail,
            $fromName,
            $to,
            $subject,
            $bodyPlain,
            $bodyHtml
        );
    }

    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . (strpos($fromName, ',') !== false ? '"' . str_replace('"', '\\"', $fromName) . '"' : $fromName) . ' <' . $fromEmail . '>',
        'X-Mailer: Systemiks',
    ];
    return @mail($to, $subject, $bodyPlain, implode("\r\n", $headers));
}

/**
 * Send email via SMTP (socket-based, no Composer).
 */
function send_mail_smtp(
    string $host,
    int $port,
    string $user,
    string $pass,
    string $encryption,
    string $fromEmail,
    string $fromName,
    string $to,
    string $subject,
    string $bodyPlain,
    string $bodyHtml = ''
): bool {
    $useTls = strtolower($encryption) === 'tls';
    $useSsl = strtolower($encryption) === 'ssl';
    $targetHost = ($useSsl ? 'ssl://' : '') . $host;
    $targetPort = $port ?: ($useSsl ? 465 : 587);
    $errno = 0;
    $errstr = '';
    $sock = @stream_socket_client(
        $targetHost . ':' . $targetPort,
        $errno,
        $errstr,
        15,
        STREAM_CLIENT_CONNECT,
        $useSsl ? null : stream_context_create(['ssl' => ['verify_peer' => false]])
    );
    if (!$sock) {
        return false;
    }
    $read = [$sock];
    $write = null;
    $except = null;
    stream_set_timeout($sock, 15);

    $readLine = function () use ($sock) {
        $line = @fgets($sock, 8192);
        return $line !== false ? rtrim($line, "\r\n") : false;
    };
    $send = function ($line) use ($sock) {
        return @fwrite($sock, $line . "\r\n") !== false;
    };

    $resp = $readLine();
    if ($resp === false || (int) substr($resp, 0, 1) !== 2) {
        fclose($sock);
        return false;
    }

    $send('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    while (($r = $readLine()) !== false && $r[0] === '-') { }
    if ($useTls && $r !== false && strpos($r, '250') === 0) {
        $send('STARTTLS');
        $readLine();
        $ctx = stream_context_create(['ssl' => ['verify_peer' => false]]);
        if (!@stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($sock);
            return false;
        }
        $send('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
        while (($r = $readLine()) !== false && $r[0] === '-') { }
    }

    if ($user !== '') {
        $send('AUTH LOGIN');
        $readLine();
        $send(base64_encode($user));
        $readLine();
        $send(base64_encode($pass));
        $r = $readLine();
        if ($r === false || (int) substr($r, 0, 1) !== 2) {
            fclose($sock);
            return false;
        }
    }

    $send('MAIL FROM:<' . $fromEmail . '>');
    $r = $readLine();
    if ($r === false || (int) substr($r, 0, 1) !== 2) {
        fclose($sock);
        return false;
    }
    $send('RCPT TO:<' . $to . '>');
    $r = $readLine();
    if ($r === false || (int) substr($r, 0, 1) !== 2) {
        fclose($sock);
        return false;
    }
    $send('DATA');
    $readLine();

    $headers = "From: " . (strpos($fromName, ',') !== false ? '"' . str_replace('"', '\\"', $fromName) . '"' : $fromName) . " <{$fromEmail}>\r\n";
    $headers .= "To: {$to}\r\n";
    $headers .= "Subject: " . $subject . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    if ($bodyHtml !== '') {
        $boundary = '----=_Part_' . bin2hex(random_bytes(8));
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";
        $body = "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n" . $bodyPlain . "\r\n";
        $body .= "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n" . $bodyHtml . "\r\n";
        $body .= "--{$boundary}--";
    } else {
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body = $bodyPlain;
    }
    $send($headers . "\r\n" . $body);
    $send('.');

    $r = $readLine();
    fclose($sock);
    return $r !== false && (int) substr($r, 0, 1) === 2;
}

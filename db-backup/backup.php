<?php namespace App;

$path = '/home/tractrak/tractrak/db-backup';

$date = date("Y-m-d");

$filename = "$date-split.gz";

exec("mysqldump --defaults-extra-file=/home/tractrak/tractrak/db-backup/config.cnf tractrak > $path/$date.sql");
exec("gzip -c $path/$date.sql | split -a 1 -d -b 20m - $path/$date-split.gz");

$count = 0;

while (true)
{
    if (file_exists("$path/$filename$count"))
    {
        ++$count;
    }
    else break;
}
$total = $count;
--$count;
$emails = 0;
while (true)
{
    if (file_exists("$path/$filename$count"))
    {
        $filenum = $count + 1;
        $message = "File $filenum of $total.";
        if (email_file($path, $filename.$count, $message) )
        {
            unlink("$path/$filename$count");
            echo "Email success $filenum of $total." . PHP_EOL;
        }
        else {
            throw new \RuntimeException("Email failed $filenum of $total.");
        }
        
        --$count;
        ++$emails;
        if ($emails > 10) {
            throw new \RuntimeException("I've sent 10 emails, something is probably broken.");
        }
    }
    else break;
}

unlink("$path/$date.sql");

function email_file($path, $filename, $message)
{
    $file = "$path/$filename";
    $subject = 'TracTrak Backup Database File';
    $mailto = 'Michael Hoppes <hoppes@gmail.com>';
    
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // main header (multipart mandatory)
    $headers = "From: Michael Hoppes <michael@tractrak.com>" . PHP_EOL;
    $headers .= "MIME-Version: 1.0" . PHP_EOL;
    $headers .= "Content-Type: multipart/mixed; boundary=\"$separator\"" . PHP_EOL;
    $headers .= "Content-Transfer-Encoding: 7bit" . PHP_EOL;
    $headers .= "This is a MIME encoded message." . PHP_EOL;

    // message
    $message = "--$separator" . PHP_EOL;
    $message .= 'Content-Type: text/plain; charset="iso-8859-1"' . PHP_EOL;
    $message .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL;
    $message .= $message . PHP_EOL;

    // attachment
    $message .= "--$separator" . PHP_EOL;
    $message .= "Content-Type: application/octet-stream; name=\"$filename\"" . PHP_EOL;
    $message .= "Content-Transfer-Encoding: base64" . PHP_EOL;
    $message .= "Content-Disposition: attachment" . PHP_EOL;
    $message .= $content . PHP_EOL;
    $message .= "--$separator--";

    //SEND Mail
    if (mail($mailto, $subject, $message, $headers))
        return true;
    else
        return false;
}

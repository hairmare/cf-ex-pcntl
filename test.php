<?php
declare(ticks=1);

$pid = pcntl_fork();
if ($pid == -1) {
     die("could not fork"); 
} else if ($pid) {
     $msg = "parent exit\n";
    file_put_contents(__DIR__.'/out.txt', $msg, FILE_APPEND);
     exit(); // we are the parent 
} else {
    $msg =  "Starting Child\n";
    file_put_contents(__DIR__.'/out.txt', $msg, FILE_APPEND);
     // we are the child
}

// detatch from the controlling terminal
if (posix_setsid() == -1) {
    die("could not detach from terminal");
}

// setup signal handlers
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");

// loop forever performing tasks
while (1) {

    // do something interesting here
    $msg = "running job\n";
    file_put_contents(__DIR__.'/out.txt', $msg, FILE_APPEND);
    sleep(5);

}

function sig_handler($signo) 
{

     switch ($signo) {
         case SIGTERM:
             // handle shutdown tasks
                $msg = "Terminating\n";
    file_put_contents(__DIR__.'/out.txt', $msg, FILE_APPEND);
             exit;
             break;
         case SIGHUP:
             // handle restart tasks
                $msg = "got HUP\n";
    file_put_contents(__DIR__.'/out.txt', $msg, FILE_APPEND);
             break;
         default:
             // handle all other signals
    file_put_contents(__DIR__.'/out.txt', $msg, FILE_APPEND);
                $msg = "Got signal ".((string) $signo)."\n";
     }

}

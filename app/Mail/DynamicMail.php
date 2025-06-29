<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public string $subjectLine;

    public ?string $viewPath;

    public array $data;

    public string $type;

    public string|array $plainBody;

    public $attachments;

    public int $priority;

    public function __construct(
        string $subject,
        ?string $viewPath = null,
        array $data = [],
        string $type = 'plain',
        string|array $plainBody = '',
        array $attachments = [],
        int $priority = 3
    ) {
        $this->subjectLine = $subject;
        $this->viewPath = $viewPath;
        $this->data = $data;
        $this->type = $type;
        $this->plainBody = $plainBody;
        $this->attachments = $attachments;
        $this->priority = $priority;

        $this->subject($this->subjectLine);
    }

    public function build()
    {
        if ($this->type === 'plain') {
            $this->withSwiftMessage(function ($message) {
                $message->setPriority($this->priority);
            });

            $this->text('emails.empty_plain') // ملف view فارغ أو يمكنك استبداله بملف نصي فارغ
                ->with(['body' => $this->plainBody]);
            $this->setBody($this->plainBody, 'text/plain');

            return $this;
        }

        if ($this->type === 'view' && $this->viewPath) {
            $this->withSwiftMessage(function ($message) {
                $message->setPriority($this->priority);
            });

            $this->view($this->viewPath, $this->data);
        } elseif ($this->type === 'markdown' && $this->viewPath) {
            $this->withSwiftMessage(function ($message) {
                $message->setPriority($this->priority);
            });

            $this->markdown($this->viewPath, $this->data);
        }

        // إرفاق الملفات
        foreach ($this->attachments as $file) {
            $this->attach($file);
        }

        return $this;
    }
}

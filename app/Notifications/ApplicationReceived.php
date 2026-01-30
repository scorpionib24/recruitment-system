<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vacancy;
use App\Models\Candidate; // <-- تم تصحيح الخطأ الإملائي هنا

class ApplicationReceived extends Notification
{
    use Queueable;

    protected $vacancy;
    protected $candidate;

    /**
     * Create a new notification instance.
     *
     * @param Vacancy $vacancy
     * @param Candidate $candidate
     */
    // =================================================
    // === هذا هو الجزء الذي تم تصحيحه (الأهم) ===
    // =================================================
    public function __construct(Vacancy $vacancy, Candidate $candidate)
    {
        $this->vacancy = $vacancy;
        $this->candidate = $candidate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('vacancies.show', $this->vacancy->id);

        return (new MailMessage)
                    ->subject('تأكيد استلام طلب التقديم لوظيفة: ' . $this->vacancy->title)
                    ->greeting('مرحباً ' . $this->candidate->first_name . ',')
                    ->line('شكراً لاهتمامك بالانضمام إلى فريقنا.')
                    ->line('نود أن نؤكد أننا قد استلمنا طلبك بنجاح للتقديم على وظيفة:')
                    ->line('**' . $this->vacancy->title . '**')
                    ->line('سيقوم فريق التوظيف بمراجعة طلبك، وسنتواصل معك في حال تطابق مؤهلاتك مع متطلبات الوظيفة.')
                    ->action('عرض تفاصيل الوظيفة مرة أخرى', $url)
                    ->salutation('مع أطيب التحيات،')
                    ->line(config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}   
<?php namespace Tests\Models;

use App\Models\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Notification $notification */
        $notification = new Notification();
        $this->assertNotNull($notification);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Notification $notification */
        $notificationModel = new Notification();

        $notificationData = factory(Notification::class)->make();
        foreach( $notificationData->toArray() as $key => $value ) {
            $notificationModel->$key = $value;
        }
        $notificationModel->save();

        print_r($notificationModel->data);

        $this->assertNotNull(Notification::find($notificationModel->id));
    }

}

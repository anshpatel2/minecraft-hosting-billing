### Step 1: Create a New Laravel Project

1. **Install Laravel**: If you haven't installed Laravel yet, you can do so via Composer. Open your terminal and run:

   ```bash
   composer create-project --prefer-dist laravel/laravel laravel-notification-system
   ```

2. **Navigate to the Project Directory**:

   ```bash
   cd laravel-notification-system
   ```

3. **Set Up Environment Variables**: Copy the `.env.example` file to `.env` and configure your database settings.

   ```bash
   cp .env.example .env
   ```

   Then, edit the `.env` file to set your database connection details.

4. **Generate Application Key**:

   ```bash
   php artisan key:generate
   ```

### Step 2: Set Up Authentication

1. **Install Laravel Breeze for Authentication**:

   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   npm install && npm run dev
   php artisan migrate
   ```

2. **Run Migrations**: This will create the necessary tables for users.

   ```bash
   php artisan migrate
   ```

### Step 3: Create Notification System

1. **Create Notification Class**:

   Run the following command to create a notification class:

   ```bash
   php artisan make:notification PlanPurchased
   ```

   This will create a new notification class in `app/Notifications/PlanPurchased.php`.

2. **Implement Notification Logic**:

   Open `app/Notifications/PlanPurchased.php` and implement the `toMail` method:

   ```php
   namespace App\Notifications;

   use Illuminate\Bus\Queueable;
   use Illuminate\Contracts\Queue\ShouldQueue;
   use Illuminate\Notifications\Messages\MailMessage;
   use Illuminate\Notifications\Notification;

   class PlanPurchased extends Notification
   {
       use Queueable;

       protected $plan;

       public function __construct($plan)
       {
           $this->plan = $plan;
       }

       public function via($notifiable)
       {
           return ['mail'];
       }

       public function toMail($notifiable)
       {
           return (new MailMessage)
               ->subject('Plan Purchase Confirmation')
               ->line('You have successfully purchased the plan: ' . $this->plan)
               ->action('View Plan', url('/plans'))
               ->line('Thank you for using our application!');
       }
   }
   ```

### Step 4: Send Notifications on Plan Purchase

1. **Create a Controller for Handling Purchases**:

   Run the following command:

   ```bash
   php artisan make:controller PurchaseController
   ```

2. **Implement Purchase Logic**:

   Open `app/Http/Controllers/PurchaseController.php` and implement the purchase logic:

   ```php
   namespace App\Http\Controllers;

   use App\Models\User;
   use App\Notifications\PlanPurchased;
   use Illuminate\Http\Request;

   class PurchaseController extends Controller
   {
       public function purchase(Request $request)
       {
           // Validate and process the purchase
           $plan = $request->input('plan'); // Assume plan is sent in the request
           $user = auth()->user();

           // Send notification to the user
           $user->notify(new PlanPurchased($plan));

           return response()->json(['message' => 'Plan purchased and notification sent.']);
       }
   }
   ```

3. **Define Routes**:

   Open `routes/web.php` and add a route for the purchase:

   ```php
   use App\Http\Controllers\PurchaseController;

   Route::post('/purchase', [PurchaseController::class, 'purchase'])->middleware('auth');
   ```

### Step 5: Allow Admin to Send Notifications

1. **Create Admin Notification Controller**:

   Run the following command:

   ```bash
   php artisan make:controller AdminNotificationController
   ```

2. **Implement Admin Notification Logic**:

   Open `app/Http/Controllers/AdminNotificationController.php` and implement the logic:

   ```php
   namespace App\Http\Controllers;

   use App\Models\User;
   use App\Notifications\PlanPurchased;
   use Illuminate\Http\Request;

   class AdminNotificationController extends Controller
   {
       public function sendNotification(Request $request)
       {
           $request->validate([
               'user_id' => 'required|exists:users,id',
               'message' => 'required|string',
           ]);

           $user = User::find($request->user_id);
           $user->notify(new PlanPurchased($request->message));

           return response()->json(['message' => 'Notification sent.']);
       }
   }
   ```

3. **Define Admin Notification Route**:

   Open `routes/web.php` and add a route for admin notifications:

   ```php
   Route::post('/admin/send-notification', [AdminNotificationController::class, 'sendNotification'])->middleware('auth');
   ```

### Step 6: Testing the Notification System

1. **Test User Purchase**: Use Postman or a similar tool to send a POST request to `/purchase` with the necessary data.

2. **Test Admin Notification**: Send a POST request to `/admin/send-notification` with the user ID and message.

### Step 7: Configure Mail Settings

Make sure to configure your mail settings in the `.env` file to enable email notifications. For example:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Conclusion

You now have a basic Laravel application with a notification system that automatically sends notifications upon plan purchase verification and allows the admin to send notifications to users. You can further enhance this system by adding features like queueing notifications, customizing notification channels, and improving the user interface.
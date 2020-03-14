##### 一个基于PSR-14的事件调度器实现
 
 https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-14-event-dispatcher.md
 
###### 定义一个事件

 ```php
 namespace app\Events;
 
 class UserLogin
 {
     public $user;
 
     public function __construct($user)
     {
         $this->user = $user;    
     }
 }
```


 ```php
 namespace app\Events;
 
 class UserLogout
 {
     public $user;
 
     public function __construct($user)
     {
         $this->user = $user;    
     }
 }
```

###### 定义一个监听器
```php
use App\Events\UserLogin;
use JigsawPuzzles\EventDispatcher\Listener;

class UserLoginListener extends Listener
{
    public function events(): array
    {
        return [
            UserLogin::class => 'handleLogin',
            UserLogout::class => ['handleLogout', 100], // 后面的数字为优先级
        ];
    }

    /**
     * @param UserLogin $event
     */
    public function handleLogin($event)
    {
        // pass
    }

    /**
     * @param UserLogout $event
     */
    public function handleLogout($event)
    {
        
    }


}
```

##### 流程
```php
$provider = new JigsawPuzzles\EventDispatcher\ListenerProvider();

$listener = new UserLoginListener();
$listener->attachListeners($provider);

$dispatcher = new \JigsawPuzzles\EventDispatcher\EventDispatcher($provider);

// 分发事件
$dispatcher->dispatch(new UserLogin());
```


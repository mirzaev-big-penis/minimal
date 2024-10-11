The MINIMAL framework that does **not limit your project with its own rules**, has **no dependencies**, implements the **best practices** of popular MVC-frameworks, it **VERY fast** and **optimized** for all the innovations in **PHP 8.2** 🤟

Can be configured to work with **any database** `core::$session` and **any HTML template engine** `$this->view`
*personally, i prefer **ArangoDB** and **Twig***

## Nearest plans (first half of 2025)
1. Add **middlewares** technology
2. Route sorting in the router `router::sort()`
3. Add trigger routes from within routes
4. Think about adding asynchronous executions
5. Write an article describing the principles of the framework

## Installation 
Execute: `composer require mirzaev/minimal`

## Usage
*index.php*
```php
// Initializing the router
$router = new router;

// Initializing of routes
$router
    ->write('/', 'catalog', 'index', 'GET')
	->write('/search', 'catalog', 'search', 'POST')
	->write('/session/connect/telegram', 'session', 'telegram', 'POST')
	->write('/product/$id', 'catalog', 'product', 'POST')
	->write('/$categories...', 'catalog', 'index', 'POST'); // Collector (since 0.3.0)

// Initializing the core
$core = new core(namespace: __NAMESPACE__, router: $router, controller: new controller(false), model: new model(false));

// Handle the request
echo $core->start();
```

## Examples of projects based on MINIMAL

### ebala
**Repository:** https://git.mirzaev.sexy/mirzaev/ebala<br>
**Github mirror:** https://github.com/mature-woman/ebala<br>
*I earned more than a **million rubles** from this project*<br>
*Repositories **may** be closed at the request of the customer*<br>

### huesos 
**Repository:** https://git.mirzaev.sexy/mirzaev/huesos<br>
**Guthub mirror:** https://github.com/mature-woman/huesos<br>
*The basis for developing chat-robots with Web App technology (for example for Telegram)*<br>

### arming_bot 
**Repository:** https://git.mirzaev.sexy/mirzaev/arming_bot<br>
**Guthub mirror:** https://github.com/mature-woman/arming_bot<br>
*Chat-robot based on huesos*<br>

### notchat
**Repository:** https://git.mirzaev.sexy/mirzaev/notchat<br>
**Github mirror:** https://github.com/mature-woman/notchat<br>
*P2P chat project with different blockchains and smart stuff*<br>

### site-repression
**Link:** https://repression.mirzaev.sexy<br>
**Repository:** https://git.mirzaev.sexy/mirzaev/site-repression<br>
**Github mirror:** https://github.com/mature-woman/site-repression<br>
*A simple site for my article about **political repressions in Russia** and my **kidnapping by Wagner PMC operatives** from my house*<br>

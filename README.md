🤟 The MINIMAL framework that does **not limit your project with its own rules**, has **no dependencies**, implements the **best practices** of popular MVC-frameworks, it **VERY fast** and **optimized** for all the innovations in **PHP 8.2**

Can be configured to work with **any database** (`core::$session`) and **any HTML template engine** (`$this->view`) 
*personally, i prefer **ArangoDB** and **Twig***

## Nearest plans (first half of 2025)
1. Add **middlewares** technology
2. Route sorting in the router: `router::sort()`
3. Add trigger routes from within routes
4. Think about adding asynchronous executions
5. Write an article describing the principles of the framework

## Installation 
Execute: `composer require mirzaev/minimal`

## Usage
*index.php*
```
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

### ebala (⚠️VERY HUGE)
Repository: https://git.mirzaev.sexy/mirzaev/ebala
Github mirror: https://github.com/mature-woman/ebala
**I earned more than a million rubles from this project**
**Repositories *may* be closed at the request of the customer**

### notchat
Repository: https://git.mirzaev.sexy/mirzaev/notchat
Github mirror: https://github.com/mature-woman/notchat
**P2P chat project with different blockchains and smart stuff**

### site-repression
Link: https://repression.mirzaev.sexy
Repository: https://git.mirzaev.sexy/mirzaev/site-repression
Github mirror: https://github.com/mature-woman/site-repression
**A simple site for my article about *political repressions in Russia* and my *abduction by Wagner PMC operatives* from my home**

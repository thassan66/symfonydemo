Symfony Demo Application (Custom CRUD Operation)
========================

The "Symfony Demo Application" is a reference application created to show how to develop applications following the [Symfony Best Practices][1].

Requirements
------------

  * PHP 8.0.0 or higher;
  * [usual Symfony application requirements][2].

Installation
------------

[Download Symfony][5] to install the `symfony` binary on your computer and run
this command:

```bash
$ symfony new my_project_name --full
```

Alternatively, you can use Composer:

```bash
$ composer create-project symfony/symfony-demo my_project_name
```

Usage
-----

There's no need to configure anything to run the application. If you have
[installed Symfony][4] binary, run this command:

```bash
$ cd my_project_name/
$ symfony server:start
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

If you don't have the Symfony binary installed, run `php -S localhost:8000 -t public/`
to use the built-in PHP web server or [configure a web server][3] like Nginx or
Apache to run the application.



[1]: https://symfony.com/doc/current/best_practices.html
[2]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[4]: https://symfony.com/download

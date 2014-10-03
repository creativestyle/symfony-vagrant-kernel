Symfony Vagrant Kernel
======================

This package can speed up your Symfony dev installation when you're using Vagrant.

If your application is residing on your host os' drive and it's shared with Vagrant box over network then symfony
cache and logs IO will kill your performance. This kernel extension configures your application to store cache
and logs in /dev/shm.

The only drawback I can think of is that the whole cache (possible including sessions) will be lost once you
power down the Vagrant box. Oh, and /dev/shm was probably designed for sharing...

WARNING! This is not for production. Use at your own risk!
WARNING! The logs will be in /dev/shm/(symfony2|projectname)/logs.

## Installation

Add  `"cs/symfony-vagrant-kernel": "1.0",` to your `composer.json`.

Change the line `use Symfony\Component\HttpKernel\Kernel;` to `use CS\Vagrant\Kernel;` in your `app/AppKernel.php`.

### Multiple projects on the same Vagrant box

If you have multiple projects running on the same vagrant box then you should define your "project name" which is
basically the name of `/dev/shm` subdirectory which will be used to store your cache. If you don't do this then
prepare for chaos to ensue.

Override the method `getProjectName`, so it looks sth like this:

```php
protected function getProjectName()
{
    return 'some_unique_string_preferably_the_name_of_your_project';
}
```

### Configure when to attempt to use the memory cache.

By default the kernel will enable `/dev/shm` caching when following conditions are met:
* You have a writable `/dev/shm` directory.
* `/home/vagrant` exists in your filesystem.

You can change the last condition by overriding the method `useMemoryCaching` in your kernel.
For example if you want this feature to be used always in your dev environment do sth like this:

```php
protected function useMemoryCaching()
{
    return $this->getEnvironment() === 'dev';
}
```

## Other considerations

I suppose you could use `/tmp` but doesn't memory cache sound better?

## Acknowledgment

This code is based on the idea presented by [Benjamin Eberlei](http://www.whitewashing.de/2013/08/19/speedup_symfony2_on_vagrant_boxes.html) and later expanded by [Jakub Kanclerz](https://gist.github.com/jkanclerz/d97bc7fd7e395688240a).
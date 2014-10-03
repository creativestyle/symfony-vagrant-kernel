<?php

/* This code is based on the idea presented by
 * Benjamin Eberlei at http://www.whitewashing.de/2013/08/19/speedup_symfony2_on_vagrant_boxes.html
 * and later expanded by
 * Jakub Kanclerz at https://gist.github.com/jkanclerz/d97bc7fd7e395688240a */

namespace CS\Vagrant;

use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

abstract class Kernel extends SymfonyKernel
{
    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        if ($this->isMemoryCachingEnabled()) {
            return $this->getMemoryCacheDir() . '/cache/' . $this->getEnvironment();
        }

        return parent::getCacheDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        if ($this->isMemoryCachingEnabled()) {
            return $this->getMemoryCacheDir() . '/logs';
        }

        return parent::getLogDir();
    }

    /**
     * @return string
     */
    protected function getMemoryCacheDir()
    {
        return '/dev/shm/' . $this->getProjectName();
    }

    /**
     * @return string
     */
    protected function getProjectName()
    {
        return 'symfony2';
    }

    /**
     * @return bool
     */
    protected function isMemoryCachingSupported()
    {
        return is_dir('/dev/shm') && is_writable('/dev/shm');
    }

    /**
     * @return bool
     */
    protected function isMemoryCachingEnabled()
    {
        return $this->useMemoryCaching() && $this->isMemoryCachingSupported();
    }

    /**
     * @return bool
     */
    protected function useMemoryCaching()
    {
        return is_dir('/home/vagrant');
    }
}
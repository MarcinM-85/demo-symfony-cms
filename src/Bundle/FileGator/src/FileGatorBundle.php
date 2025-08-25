<?php //
namespace App\Bundle\FileGator;

use App\Bundle\FileGator\DependencyInjection\FileGatorExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FileGatorBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (!isset($this->extension)) {
            $this->extension = new FileGatorExtension();
        }

        return $this->extension ?: null;
    }
}
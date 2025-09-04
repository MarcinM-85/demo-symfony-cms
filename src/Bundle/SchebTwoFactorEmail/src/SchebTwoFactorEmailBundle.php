<?php //
namespace App\Bundle\SchebTwoFactorEmail;

use App\Bundle\SchebTwoFactorEmail\DependencyInjection\SchebTwoFactorEmailExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SchebTwoFactorEmailBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (!isset($this->extension)) {
            $this->extension = new SchebTwoFactorEmailExtension();
        }

        return $this->extension ?: null;
    }
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
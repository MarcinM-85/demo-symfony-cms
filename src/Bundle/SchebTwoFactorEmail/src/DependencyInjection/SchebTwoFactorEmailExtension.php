<?php
namespace App\Bundle\SchebTwoFactorEmail\DependencyInjection;

use App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\EmailAuthCodeTwoFactorProvider;
use App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\Generator\CodeGenerator;
use App\Bundle\SchebTwoFactorEmail\Security\Provider\EmailAuthCode\Mailer\EmailAuthCodeMailer;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\DefaultTwoFactorFormRenderer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class SchebTwoFactorEmailExtension extends Extension
{
    public const ALIAS = "scheb_2fa_email";

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration(self::ALIAS);
        $config = $this->processConfiguration($configuration, $configs);

        // Parametry
        $container->setParameter('scheb_2fa_email.code_generator.digits', $config['code_generator']['digits']);
        $container->setParameter('scheb_2fa_email.code_generator.expires_after', $config['code_generator']['expires_after']);
        $container->setParameter('scheb_2fa_email.email.sender_email', $config['email']['sender_email']);
        $container->setParameter('scheb_2fa_email.email.sender_name', $config['email']['sender_name']);
        $container->setParameter('scheb_2fa_email.email.subject', $config['email']['subject']);
        $container->setParameter('scheb_2fa_email.email.template', $config['email']['template'] ?? '@SchebTwoFactorEmail/email/auth_code.html.twig');
        $container->setParameter('scheb_2fa_email.form_renderer.template', $config['form_renderer']['template'] ?? '@SchebTwoFactorEmail/2fa.html.twig');

        // DefaultTwoFactorFormRenderer
        $container->register(DefaultTwoFactorFormRenderer::class, DefaultTwoFactorFormRenderer::class)
            ->setAutowired(true)//[OR] ->setArgument('$twigEnvironment', new Reference('twig'))
            ->setArgument('$template', '%scheb_2fa_email.form_renderer.template%');

        // EmailAuthCodeMailer
        $container->register(EmailAuthCodeMailer::class, EmailAuthCodeMailer::class)
            ->setAutowired(true)//[OR] ->setArgument('$twig', new Reference('twig'))->setArgument('$twig', new Reference('mailer'))
            ->setArgument('$senderEmail', '%scheb_2fa_email.email.sender_email%')
            ->setArgument('$senderName', '%scheb_2fa_email.email.sender_name%')
            ->setArgument('$emailSubject', '%scheb_2fa_email.email.subject%')
            ->setArgument('$emailTemplate', '%scheb_2fa_email.email.template%');

        // CodeGenerator
        $container->register(CodeGenerator::class, CodeGenerator::class)
            ->setAutowired(true)//[OR] ->setArgument('$persister', new Reference(\Scheb\TwoFactorBundle\Model\PersisterInterface::class))->setArgument('$clock', new Reference(\Psr\Clock\ClockInterface::class))
            ->setArgument('$mailer', new Reference(EmailAuthCodeMailer::class))
            ->setArgument('$digits', '%scheb_2fa_email.code_generator.digits%')
            ->setArgument('$expiresAfter', '%scheb_2fa_email.code_generator.expires_after%');

        // TwoFactorProvider
        $container->register(EmailAuthCodeTwoFactorProvider::class, EmailAuthCodeTwoFactorProvider::class)
            ->setAutowired(true)// [OR]->setArgument('$eventDispatcher', new Reference(\Symfony\Contracts\EventDispatcher\EventDispatcherInterface::class))->setArgument('$clock', new Reference(\Psr\Clock\ClockInterface::class))
            ->setArgument('$codeGenerator', new Reference(CodeGenerator::class))
            ->setArgument('$formRenderer', new Reference(DefaultTwoFactorFormRenderer::class))
            ->addTag('scheb_two_factor.provider', ['alias' => 'email_auth_code']);

//        // ładowanie ewentualnych plików konfiguracyjnych z Resources/config
//        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
//        if (file_exists(__DIR__ . '/../../config/services.yaml')) {
//            $loader->load('services.yaml');
//        }
    }

    public function prepend(ContainerBuilder $container): void
    {
        //Przestrzen nazw @SchebTwoFactorEmail, ktora wskazuje na katalog templates tego Bundle
        $container->prependExtensionConfig('twig', [
            'paths' => [
                realpath(__DIR__ . '/../../templates') => 'SchebTwoFactorEmail',
            ],
        ]);
    }

    public function getAlias(): string {
        return self::ALIAS;
    }
}
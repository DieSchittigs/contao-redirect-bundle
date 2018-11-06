<?php 

declare(strict_types=1);

namespace DieSchittigs\RedirectBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DieSchittigs\RedirectBundle\ContaoRedirectBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoRedirectBundle::class)
                ->setLoadAfter([
                    'Contao\CoreBundle\ContaoCoreBundle',
                    'Contao\CalendarBundle\ContaoCalendarBundle'
                ])
                ->setReplace(['redirects']),
        ];
    }
}
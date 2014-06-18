<?php
// src/Acme/DemoBundle/Twig/AcmeExtension.php
namespace Wa\FrontBundle\Twig;

class HtmlTruncate extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('truncateHtml', array($this, 'truncateHtml')),
        );
    }

    public function truncateHtml($src, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        return $src;
    }

    public function getName()
    {
        return 'wa_html_truncate_extension';
    }
}

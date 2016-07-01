<?php

namespace eLife\Journal\ViewModel;

use eLife\Patterns\ViewModel\Button;
use eLife\Patterns\ViewModel\Image;
use eLife\Patterns\ViewModel\Link;
use eLife\Patterns\ViewModel\NavLinkedItem;
use eLife\Patterns\ViewModel\PictureSvgWithFallback;
use eLife\Patterns\ViewModel\SiteHeader;
use eLife\Patterns\ViewModel\SiteHeaderNavBar;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use Puli\UrlGenerator\Api\UrlGenerator as PuliUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SiteHeaderFactory
{
    private $urlGenerator;
    private $puliUrlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, PuliUrlGenerator $puliUrlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->puliUrlGenerator = $puliUrlGenerator;
    }

    public function createSiteHeader() : PromiseInterface
    {
        $primaryLinks = SiteHeaderNavBar::primary([
            NavLinkedItem::asIcon(new Link('Menu', '#mainMenu'),
                new PictureSvgWithFallback(
                    [
                        ['svg' => $this->puliUrlGenerator->generateUrl('/elife/patterns/assets/img/patterns/molecules/nav-primary-menu-ic.svg')],
                    ],
                    new Image(
                        $this->puliUrlGenerator->generateUrl('/elife/patterns/assets/img/patterns/molecules/nav-primary-menu-ic_1x.png'),
                        [
                            48 => $this->puliUrlGenerator->generateUrl('/elife/patterns/assets/img/patterns/molecules/nav-primary-menu-ic_2x.png'),
                            24 => $this->puliUrlGenerator->generateUrl('/elife/patterns/assets/img/patterns/molecules/nav-primary-menu-ic_1x.png'),
                        ],
                        'Menu icon',
                        ['nav-primary__menu_icon']
                    )
                )
            ),
            NavLinkedItem::asLink(new Link('Home', $this->urlGenerator->generate('home'))),
            NavLinkedItem::asLink(new Link('Magazine', $this->urlGenerator->generate('magazine'))),
        ]);

        $secondaryLinks = SiteHeaderNavBar::secondary([
            NavLinkedItem::asLink(new Link('Careers', $this->urlGenerator->generate('careers'))),
            NavLinkedItem::asLink(new Link('About', $this->urlGenerator->generate('about'))),
            NavLinkedItem::asLink(new Link('Labs', $this->urlGenerator->generate('labs'))),
            NavLinkedItem::asButton(
                Button::link('Submit my research', 'http://submit.elifesciences.org/', Button::SIZE_EXTRA_SMALL)
            ),
        ]);

        return new FulfilledPromise(
            new SiteHeader($this->urlGenerator->generate('home'), $primaryLinks, $secondaryLinks)
        );
    }
}

<?php

namespace Prime\EzSiteMap\Sitemap;

use DOMDocument;

/**
 * Class Sitemap
 * @package Prime\eZ\Sitemap
 */
class Sitemap
{
    /**
     * @var \DOMElement
     */
    protected $urlSet;

    /**
     * @var \DOMDocument
     */
    protected $doc;

    /**
     * 
     */
    public function __construct()
    {
        $this->doc = new DOMDocument("1.0", 'UTF-8');
        $this->urlSet = $this->doc->createElement('urlset');
        $this->urlSet->setAttribute( "xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9" );
    }

    /**
     *
     */
    public function addEntry( $mainUrl, $modified, $priority = 0.5, $alternateUrls = false )
    {
        $urlEl      = $this->doc->createElement( 'url' );
        $loc        = $this->doc->createElement( 'loc', $mainUrl );
        $lastMod    = $this->doc->createElement( 'lastmod', $modified );
        $priority   = $this->doc->createElement( 'priority', $priority );

        $urlEl->appendChild( $loc );
        $urlEl->appendChild( $lastMod );
        $urlEl->appendChild( $priority );
        if( $alternateUrls ) {
            foreach( $alternateUrls as $alternateUrl ) {
                $hreflang = $this->doc->createAttribute('hreflang');
                $hreflang->value = $alternateUrl['languageCode'];

                $rel = $this->doc->createAttribute('rel');
                $rel->value = "alternate";

                $href = $this->doc->createAttribute('href');
                $href->value = $alternateUrl['url'];

                $link = $this->doc->createElementNS('http://www.w3.org/1999/xhtml', 'xhtml:link');
                $link->appendChild( $hreflang );
                $link->appendChild( $rel) ;
                $link->appendChild( $href );

                $urlEl->appendChild( $link );
            }
        }

        $this->urlSet->appendChild( $urlEl );
    }

    /**
     * @return string
     */
    public function export()
    {
        $this->doc->appendChild( $this->urlSet );
        $this->doc->formatOutput = true;
        return $this->doc->saveXML();
    }

}

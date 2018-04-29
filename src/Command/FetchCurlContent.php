<?php namespace Anomaly\RssFeedBlockExtension\Command;

use Anomaly\BlocksModule\Block\Contract\BlockInterface;
use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;

/**
 * Class FetchCurlContent
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class FetchCurlContent
{

    /**
     * The block instance.
     *
     * @var BlockInterface
     */
    protected $block;

    /**
     * Create a new FetchCurlContent instance.
     *
     * @param BlockInterface $block
     */
    public function __construct(BlockInterface $block)
    {
        $this->block = $block;
    }

    /**
     * Handle the command.
     *
     * @param \SimplePie                       $rss
     * @param ConfigurationRepositoryInterface $configuration
     * @return null|\SimplePie_Item[]
     */
    public function handle(\SimplePie $rss, ConfigurationRepositoryInterface $configuration)
    {
        // Let Laravel cache everything.
        $rss->enable_cache(false);

        $rss->set_feed_url(
            $configuration->value(
                'anomaly.extension.rss_feed_block::url',
                $this->block->getId(),
                'http://pyrocms.com/posts/rss.xml'
            )
        );

        // Make the request.
        $rss->init();

        return $rss->get_items(
            0,
            (int)$configuration->value(
                'anomaly.extension.rss_feed_block::count',
                $this->block->getId(),
                5
            )
        );
    }
}
